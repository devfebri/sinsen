<!-- Modal -->
<div id="h3_md_rincian_so_monitoring_supply" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 90%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Rincian PO</h4>
            </div>
            <div class="modal-body">
                <div id="rincian_sales_order" class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <form class="form-horizontal">
                                <div class="box-body">
                                    <div v-if='mode != "insert"' class="form-group">
                                        <label class="col-sm-2 control-label">No SO</label>
                                        <div class="col-sm-4">
                                            <input type="text" readonly class="form-control" v-model="sales_order.id_sales_order" />
                                        </div>
                                        <label class="col-sm-2 control-label">Tanggal SO</label>
                                        <div class="col-sm-4">
                                            <input type="text" readonly class="form-control" v-model="sales_order.tanggal_so" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Nama Customer</label>
                                        <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                                            <input type="text" readonly class="form-control" v-model="sales_order.nama_dealer" />
                                            <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>
                                        </div>
                                        <label class="col-sm-2 control-label">Kode Customer</label>
                                        <div class="col-sm-4">
                                            <input type="text" readonly class="form-control" v-model="sales_order.kode_dealer_md" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Tipe PO</label>
                                        <div v-bind:class="{ 'has-error': error_exist('po_type') }" class="col-sm-4">
                                            <input v-model='sales_order.po_type' disabled type="text" class="form-control">
                                            <small v-if="error_exist('po_type')" class="form-text text-danger">{{ get_error('po_type') }}</small>
                                        </div>
                                        <label class="col-sm-2 control-label">Alamat Customer</label>
                                        <div class="col-sm-4">
                                            <input type="text" readonly class="form-control" v-model="sales_order.alamat" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Masa Berlaku</label>
                                        <div class="col-sm-4">
                                            <input type="text" readonly class="form-control" v-model="sales_order.batas_waktu" />
                                        </div>
                                        <label class="col-sm-2 control-label">Jenis Pembayaran</label>
                                        <div v-bind:class="{ 'has-error': error_exist('jenis_pembayaran') }" class="col-sm-4">
                                            <input v-model='sales_order.jenis_pembayaran' disabled type="text" class="form-control">
                                            <small v-if="error_exist('jenis_pembayaran')" class="form-text text-danger">{{ get_error('jenis_pembayaran') }}</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kategori PO</label>
                                        <div v-bind:class="{ 'has-error': error_exist('kategori_po') }" class="col-sm-4">
                                            <input v-model='sales_order.kategori_po' disabled type="text" class="form-control">
                                            <small v-if="error_exist('kategori_po')" class="form-text text-danger">{{ get_error('kategori_po') }}</small>
                                        </div>
                                        <div>
                                            <label class="col-sm-2 control-label">Nama Salesman</label>
                                            <div v-bind:class="{ 'has-error': error_exist('id_salesman') }" class="col-sm-4">
                                                <input type="text" readonly class="form-control" v-model="sales_order.nama_salesman" />
                                                <small v-if="error_exist('id_salesman')" class="form-text text-danger">{{ get_error('id_salesman') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Produk</label>
                                        <div v-bind:class="{ 'has-error': error_exist('produk') }" class="col-sm-4">
                                            <input v-model='sales_order.produk' disabled type="text" class="form-control">
                                            <small v-if="error_exist('produk')" class="form-text text-danger">{{ get_error('produk') }}</small>
                                        </div>
                                        <label class="col-sm-2 control-label">Target Customer</label>
                                        <div v-bind:class="{ 'has-error': error_exist('target_customer') }" class="col-sm-4">
                                            <vue-numeric class="form-control" v-model='sales_order.target_customer' currency='Rp' separator='.' disabled></vue-numeric>
                                            <small v-if="error_exist('target_customer')" class="form-text text-danger">{{ get_error('target_customer') }}</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Tipe Source</label>
                                        <div v-bind:class="{ 'has-error': error_exist('tipe_source') }" class="col-sm-4">
                                            <input v-model='sales_order.tipe_source' disabled type="text" class="form-control">
                                            <small v-if="error_exist('tipe_source')" class="form-text text-danger">{{ get_error('tipe_source') }}</small>
                                        </div>
                                        <label class="col-sm-2 control-label">Actual SO</label>
                                        <div v-bind:class="{ 'has-error': error_exist('sales_order_target') }" class="col-sm-4">
                                            <vue-numeric class="form-control" v-model='sales_order.sales_order_target' currency='Rp' separator='.' disabled></vue-numeric>
                                            <small v-if="error_exist('sales_order_target')" class="form-text text-danger">{{ get_error('sales_order_target') }}</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-offset-6 control-label">% Ach SO</label>
                                        <div v-bind:class="{ 'has-error': error_exist('persentase_sales_order_target') }" class="col-sm-4">
                                            <vue-numeric class="form-control" v-model='sales_order.persentase_sales_order_target' currency-symbol-position='suffix' currency='%' precision='1' disabled></vue-numeric>
                                            <small v-if="error_exist('persentase_sales_order_target')" class="form-text text-danger">{{ get_error('persentase_sales_order_target') }}</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-offset-6 control-label">Actual Sales Out</label>
                                        <div v-bind:class="{ 'has-error': error_exist('sales_order_target_out') }" class="col-sm-4">
                                            <vue-numeric class="form-control" v-model='sales_order.sales_order_target_out' currency='Rp' separator='.' disabled></vue-numeric>
                                            <small v-if="error_exist('sales_order_target_out')" class="form-text text-danger">{{ get_error('sales_order_target_out') }}</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 col-sm-offset-6 control-label">% Ach Sales Out</label>
                                        <div v-bind:class="{ 'has-error': error_exist('persentase_sales_order_target_out') }" class="col-sm-4">
                                            <vue-numeric class="form-control" v-model='sales_order.persentase_sales_order_target_out' currency-symbol-position='suffix' currency='%' precision='1' disabled></vue-numeric>
                                            <small v-if="error_exist('persentase_sales_order_target_out')" class="form-text text-danger">{{ get_error('persentase_sales_order_target_out') }}</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table id="table" class="table table-condensed table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Part Number</th>
                                                        <th>Nama Part</th>
                                                        <th>HET</th>
                                                        <th>Diskon Satuan Dealer</th>
                                                        <th>Diskon Campaign</th>
                                                        <th v-if="kategori_sim_part">Qty SIM Part Dealer</th>
                                                        <th class='text-right'>Qty Actual Dealer</th>
                                                        <th class='text-right'>Qty AVS</th>
                                                        <th class='text-right'>Qty Order</th>
                                                        <th v-if="sales_order.created_by_md == 0" class='text-right'>Qty Terpenuhi</th>
                                                        <th width="15%" class="text-right">Nilai (Amount)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(part, index) in parts">
                                                        <td class="align-middle">{{ index + 1 }}.</td>
                                                        <td class="align-middle">{{ part.id_part }}</td>
                                                        <td class="align-middle">{{ part.nama_part }}</td>
                                                        <td class="align-middle">
                                                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.harga" currency="Rp " />
                                                        </td>
                                                        <td class="align-top">
                                                          <vue-numeric read-only v-model="part.diskon_value" :currency='get_currency_symbol(part.tipe_diskon)' :currency-symbol-position='get_currency_position(part.tipe_diskon)' separator='.'/>
                                                        </td> 
                                                        <td class="align-top">
                                                          <vue-numeric read-only v-model="part.diskon_value_campaign" :currency='get_currency_symbol(part.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(part.tipe_diskon_campaign)' separator='.'/>
                                                        </td> 
                                                        <td v-if="kategori_sim_part" class="align-middle">
                                                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_sim_part_dealer" />
                                                        </td>
                                                        <td class="align-middle text-right">
                                                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_actual_dealer" />
                                                        </td>
                                                        <td class="align-middle text-right">
                                                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_avs" />
                                                        </td>
                                                        <td class="align-middle text-right">
                                                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_order" v-on:keypress.native="qty_order_change_handler" />
                                                        </td>
                                                        <td v-if="sales_order.created_by_md == 0" class="align-middle text-right">
                                                            <vue-numeric read-only class="form-control" separator="." :empty-value="1" v-model="part.qty_pemenuhan" />
                                                        </td>
                                                        <td width="8%" class="align-middle text-right">
                                                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="sub_total(part)" />
                                                        </td>
                                                    </tr>
                                                    <tr v-if="parts.length > 0">
                                                        <td class="text-right" :colspan="total_label_coslpan">Total</td>
                                                        <td class="text-right" colspan="1">
                                                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="total" currency="Rp"></vue-numeric>
                                                        </td>
                                                    </tr>
                                                    <tr v-if="parts.length < 1">
                                                        <td class="text-center" colspan="15">Belum ada part</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </form>
                        </div>
                    </div>
              </div>
            </div>
        </div>
    </div>
</div>
<script>
    rincian_sales_order = new Vue({
        el: '#rincian_sales_order',
        data: {
          loading: false,
          errors: {},
          mode: '<?= $mode ?>',
          sales_order: {
            id_ref: '',
            id_salesman: '',
            nama_salesman: '',
            id_dealer: '',
            nama_dealer: '',
            kode_dealer_md: '',
            alamat: '',
            tipe_po: '',
            batas_waktu: '',
            kategori_po: '',
            produk: '',
            jenis_pembayaran: '',
            bulan_kpb: '',
            tipe_source: '',
            po_type: '',
            created_by_md: 1,
            target_customer: '',
            sales_order_target: '',
            persentase_sales_order_target: '',
            sales_order_out_target: '',
            persentase_sales_order_out_target: '',
          },
          parts: [],
        },
        methods: {
          get_sales_order: function(){
            post = {};
            post.id_sales_order = $('#selected_id_sales_order').val();
            this.loading = true;
            axios.post('h3/h3_md_monitoring_supply/get_sales_order', Qs.stringify(post))
            .then(function(res){
              rincian_sales_order.sales_order = res.data;
            })
            .catch(function(err){
              toastr.error(err);
            })
            .then(function(){ rincian_sales_order.loading = false; })
          },
          get_sales_order_parts: function(){
            post = {};
            post.id_sales_order = $('#selected_id_sales_order').val();
            post.id_dealer = this.sales_order.id_dealer;

            this.loading = true;
            axios.post('h3/h3_md_monitoring_supply/get_sales_order_parts', Qs.stringify(post))
            .then(function(res){
              rincian_sales_order.parts = res.data;
            })
            .catch(function(err){
              toastr.error(err);
            })
            .then(function(){ rincian_sales_order.loading = false; })
          },
          get_target_customer: function(){
            if(this.sales_order.id_dealer == '') return;
            this.loading = true;
            axios.get('h3/h3_md_sales_order/get_target_customer', {
              params: {
                id_dealer: this.sales_order.id_dealer,
                produk: this.sales_order.produk
              }
            })
            .then(function(res){
              data = res.data;
              rincian_sales_order.sales_order.target_customer = data.target_customer;
              rincian_sales_order.sales_order.sales_order_target = data.sales_order_target;
              rincian_sales_order.sales_order.persentase_sales_order_target = data.persentase_sales_order_target;
              rincian_sales_order.sales_order.sales_order_out_target = data.sales_order_out_target;
              rincian_sales_order.sales_order.persentase_sales_order_out_target = data.persentase_sales_order_out_target;
            })
            .catch(function(err){
              toastr.error(err);
            })
            .then(function(){
              app.loading = false;
            });
          },
          sub_total: function(part) {
            harga_setelah_diskon = part.harga;

            if(part.tipe_diskon == 'Rupiah'){
              harga_setelah_diskon = part.harga - part.diskon_value;
            }else if(part.tipe_diskon == 'Persen'){
              diskon = (part.diskon_value/100) * part.harga;
              harga_setelah_diskon = part.harga - diskon;
            }

            if(part.tipe_diskon_campaign == 'Rupiah'){
              harga_setelah_diskon = harga_setelah_diskon - part.diskon_value_campaign;
            }else if(part.tipe_diskon_campaign == 'Persen'){
              diskon = (part.diskon_value_campaign/100) * harga_setelah_diskon;
              harga_setelah_diskon = harga_setelah_diskon - diskon;
            }

            if(this.sales_order.created_by_md == 1){
              return (part.qty_order * harga_setelah_diskon);
            }
            return (part.qty_pemenuhan * harga_setelah_diskon);
          },
          error_exist: function(key){
            return _.get(this.errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.errors, key)
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
        },
        watch: {
          'sales_order.id_dealer': function(){
            this.get_target_customer();
          },
        },
        computed: {
          total: function(){
            total = 0;
            for(part of this.parts){
              total += this.sub_total(part);
            }
            return total;
          },
          kategori_sim_part: function () {
            return this.sales_order.kategori_po == "SIM Part";
          },
          produk_oli: function () {
            return this.sales_order.produk == "Oil";
          },
          produk_parts: function () {
            return this.sales_order.produk == "Parts";
          },
          dealer_terisi: function(){
            return this.sales_order.id_dealer != '';
          },
          total_label_coslpan: function(){
            colspan = 0;
            if(this.kategori_sim_part){
              colspan = 7;
              return;
            }

            if(this.produk_parts){
              colspan = 8;
            }

            if(this.produk_oli){
              colspan = 8;
            }

            if (this.sales_order.created_by_md) {
              colspan += 1;
            }
            return colspan;
          }
        }
      });
</script>
<script>
  $(document).ready(function(){
    $('#h3_md_rincian_so_monitoring_supply').on('show.bs.modal', function(e){
      rincian_sales_order.get_sales_order();
      rincian_sales_order.get_sales_order_parts();
      rincian_sales_order.get_target_customer();
    });
  })
</script>