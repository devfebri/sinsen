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
                <input type="text" readonly class="form-control" v-model='do_sales_order.id_do_sales_order'>                    
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
                    <vue-numeric disabled class="form-control" currency='Rp' separator='.' v-model='do_sales_order.plafon'></vue-numeric>                   
                </div>      
            </div> 
            <div class="form-group">                  
                <label class="col-sm-2 control-label">TOP</label>
                <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_sales_order.top'>
                </div>                                
                <label class="col-sm-2 control-label">Sisa Plafon</label>
                <div class="col-sm-4">                    
                    <vue-numeric disabled class="form-control" currency='Rp' separator='.' v-model='sisa_plafon'></vue-numeric>                
                </div>      
            </div> 
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Name Salesman</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_sales_order.nama_salesman'>                    
                </div>                                
                <label class="col-sm-2 control-label">Plafon Booking</label>
                <div class="col-sm-4">                    
                    <vue-numeric disabled class="form-control" currency='Rp' separator='.' v-model='do_sales_order.plafon_booking'></vue-numeric>   
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
                        <th>HET</th>              
                        <th>Qty Supply</th>
                        <th>Diskon Satuan Dealer</th>
                        <th>Diskon Campaign</th>
                        <th class='text-center'>Harga Setelah Diskon</th>
                        <th class='text-center'>Amount</th>
                        <th class='text-center'>Harga Beli</th>
                        <th class='text-center'>Selisih</th>
                    </tr>
                    </thead>
                    <tbody>            
                    <tr v-for="(part, index) in parts"> 
                        <td class="align-top">{{ index + 1 }}.</td>                       
                        <td class="align-top">{{ part.id_part }}</td>                       
                        <td class="align-top">{{ part.nama_part }}</td>                       
                        <td v-if='kategori_kpb' class="align-top">{{ part.id_tipe_kendaraan }}</td>                       
                        <td class="align-top">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.harga"/>
                        </td>
                        <td class="align-top">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.qty_supply"/>
                        </td>
                        <td class="align-top">
                            <vue-numeric read-only v-model="part.diskon_satuan_dealer" separator='.' :currency='get_currency_symbol(part.tipe_diskon_satuan_dealer)' :currency-symbol-position='get_currency_position(part.tipe_diskon_satuan_dealer)'/>
                        </td> 
                        <td class="align-top">
                            <vue-numeric read-only v-model="part.diskon_campaign" separator='.' :currency='get_currency_symbol(part.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(part.tipe_diskon_campaign)'/>
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
                    <tr v-if="parts.length > 1">
                        <td class="text-right" :colspan="sub_total_colspan">Sub Total</td>
                        <td class="text-right" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="sub_total" currency='Rp'/>
                        </td>
                    </tr>
                    <tr v-if="parts.length > 1">
                        <td colspan='2' class='align-middle'>Total Insentif</td>
                        <td class='align-middle'>
                            <vue-numeric :read-only="true" class="form-control" separator="."/>
                        </td>
                        <td :colspan='insentif_colspan'></td>
                        <td class="text-right align-middle" colspan="1">Diskon Insentif</td>
                        <td class="text-right align-middle" colspan='3'>
                            <vue-numeric read-only class="form-control" separator="." v-model="do_sales_order.diskon_insentif" currency='Rp'/>
                        </td>
                    </tr>
                    <tr v-if="parts.length > 1">
                        <td :colspan='total_colspan'></td>
                        <td class="text-right align-middle" colspan="1">Diskon Cashback</td>
                        <td class="text-right align-middle" colspan='3'>
                            <vue-numeric read-only class="form-control" separator="." v-model="do_sales_order.diskon_cashback" currency='Rp'/>
                        </td>
                    </tr>
                    <tr v-if="parts.length > 1">
                        <td :colspan='total_colspan'></td>
                        <td class="text-right align-middle" colspan="1">Total Diskon</td>
                        <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="total_diskon" currency='Rp'/>
                        </td>
                    </tr>
                    <tr v-if="parts.length > 1">
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
                <!-- <a :href="'h3/h3_md_create_faktur/cetak?id=' + do_sales_order.id_do_sales_order" class="btn btn-info btn-sm btn-flat">Create Faktur</a> -->
                <button v-if='do_sales_order.status == "Draft"' @click.prevent='approve' type="submit" class="btn btn-success btn-sm btn-flat">Approve</button>                  
                <button v-if='do_sales_order.status == "Draft"' data-toggle='modal' data-target='#reject_modal' type="button" class="btn btn-danger btn-sm btn-flat">Reject</button>                  
                <div id="reject_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title text-left" id="myModalLabel">Alasan Reject</h4>
                            </div>
                            <div class="modal-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                <textarea class="form-control" id="alasan_reject"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary">Submit</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                console.log(part.id_part);
                console.log(this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, part.harga));
                return part.harga -
                this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, part.harga) - 
                this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga);
            },
            calculate_discount: function(discount, tipe_diskon, price) {
                if(tipe_diskon == 'Percentage' || tipe_diskon == 'Persen'){
                    if(discount == 0) return 0; 

                    return discount = (discount/100) * price;
                }else if(tipe_diskon == 'Value' || tipe_diskon == 'Rupiah'){
                    return discount;
                }
                
                return 0;
            },
            amount: function(part) {
                return this.harga_setelah_diskon(part) * part.qty_supply
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
            sub_total: function(){
                total = 0;
                for (index = 0; index < this.parts.length; index++) {
                    part = this.parts[index];
                    total += this.amount(part);
                }
                return total;
            },
            total_diskon: function(){
                return this.do_sales_order.diskon_insentif + this.do_sales_order.diskon_cashback;
            },
            total: function(){
                return this.sub_total - this.total_diskon;
            },
            sisa_plafon: function(){
              return this.do_sales_order.plafon - this.do_sales_order.plafon_booking;
            }
        }
    });
</script>