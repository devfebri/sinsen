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
                <input type="text" readonly class="form-control" v-model='do_revisi.tanggal_do'>                    
                </div>                                
                <label class="col-sm-2 control-label">Nama Customer</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_revisi.nama_dealer'>                    
                </div>  
            </div>    
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Nomor DO</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" :value='get_nomor_do(do_revisi.id_do_sales_order, do_revisi.sudah_revisi)'>                    
                </div>                                
                <label class="col-sm-2 control-label">Kode Customer</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_revisi.kode_dealer'>                    
                </div>      
            </div>      
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Tanggal SO</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_revisi.tanggal_so'>                    
                </div>                                
                <label class="col-sm-2 control-label">Alamat Customer</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_revisi.alamat'>                    
                </div>      
            </div> 
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Nomor SO</label>
                <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_revisi.id_sales_order'>                    
                </div>                                
                <label class="col-sm-2 control-label">Plafon</label>
                <div class="col-sm-4">                    
                    <vue-numeric disabled class="form-control" currency='Rp' separator='.' v-model='do_revisi.plafon'></vue-numeric>                   
                </div>      
            </div> 
            <div class="form-group">                  
                <label class="col-sm-2 control-label">TOP</label>
                <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" v-model='do_revisi.top'>
                </div>                                
                <label class="col-sm-2 control-label">Sisa Plafon</label>
                <div class="col-sm-4">                    
                    <vue-numeric disabled class="form-control" currency='Rp' separator='.' v-model='sisa_plafon'></vue-numeric>                
                </div>      
            </div> 
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Name Salesman</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_revisi.nama_salesman'>                    
                </div>                                
                <label class="col-sm-2 control-label">Plafon Booking</label>
                <div class="col-sm-4">                    
                    <vue-numeric disabled class="form-control" currency='Rp' separator='.' v-model='do_revisi.plafon_booking'></vue-numeric>   
                </div>      
            </div>
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Tipe PO</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_revisi.po_type'>                    
                </div>                                
                <label class="col-sm-2 control-label">Kategori PO</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_revisi.kategori_po'>                    
                </div>      
            </div>
            <div class="form-group">                  
                <label class="col-sm-2 control-label">Status</label>
                <div class="col-sm-4">                    
                <input type="text" readonly class="form-control" v-model='do_revisi.status'>                    
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
                        <th>Qty DO</th>              
                        <th>Qty Scan/Revisi</th>
                        <th>Qty Selisih</th>
                        <th>Harga Setelah Diskon</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tr v-for="(item, index) in items"> 
                        <td class="align-middle">{{ index + 1 }}.</td>                       
                        <td class="align-middle">{{ item.id_part }}</td>                       
                        <td class="align-middle">{{ item.nama_part }}</td>                       
                        <td v-if='kategori_kpb' class="align-middle">{{ item.id_tipe_kendaraan }}</td>                       
                        <td class="align-middle">
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="item.harga_jual" currency='Rp'/>
                        </td>
                        <td class="align-middle">
                            <vue-numeric read-only v-model="item.diskon_satuan_dealer" :currency='get_currency_symbol(item.tipe_diskon_satuan_dealer)' :currency-symbol-position='get_currency_position(item.tipe_diskon_satuan_dealer)' separator='.'/>
                          </td> 
                          <td class="align-middle">
                            <vue-numeric read-only v-model="item.diskon_campaign" :currency='get_currency_symbol(item.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(item.tipe_diskon_campaign)' separator='.'/>
                          </td> 
                        <td class="align-middle">
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="item.qty_do"/>
                        </td>
                        <td class="align-middle">
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="item.qty_revisi"/>
                        </td>
                        <td>
                            <vue-numeric :read-only="true" class="form-control" separator="." :minus='true' :empty-value="1" v-model="item.qty_selisih"/>
                        </td>    
                        <td>
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="harga_setelah_diskon(item)"/>
                        </td> 
                        <td class='text-right'>
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="amount(item)"/>
                        </td> 
                    </tr>
                    <tr v-if='items.length > 0'>
                        <td :colspan='table_colspan' class='text-right'>Sub Total</td>
                        <td class='text-right'>
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="sub_total"/>
                        </td>
                    </tr>
                    <tr v-if='items.length > 0'>
                        <td :colspan='table_colspan' class='text-right'>Diskon Insentif</td>
                        <td class='text-right'>
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="do_revisi.diskon_insentif_revisi"/>
                        </td>
                    </tr>
                    <tr v-if='items.length > 0'>
                        <td :colspan='table_colspan' class='text-right'>Cashback Langsung</td>
                        <td class='text-right'>
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="do_revisi.diskon_cashback_otomatis_revisi"/>
                        </td>
                    </tr>
                    <tr v-if='items.length > 0'>
                        <td :colspan='table_colspan' class='text-right'>Diskon Cashback</td>
                        <td class='text-right'>
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="do_revisi.diskon_cashback_revisi"/>
                        </td>
                    </tr>
                    <tr v-if='items.length > 0'>
                        <td :colspan='table_colspan' class='text-right'>Total</td>
                        <td class='text-right'>
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="total"/>
                        </td>
                    </tr>
                </table>
                </div>
            </div>                                                                                                                               
            </div><!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-6 no-padding">
                    <button v-if='do_revisi.status == "Open" && do_revisi.terdapat_revisi_scan != 1' @click.prevent='approve' type="submit" class="btn btn-success btn-sm btn-flat">Approve</button>                  
                    <button v-if='do_revisi.status == "Open" && do_revisi.terdapat_revisi_scan != 1' data-toggle='modal' data-target='#reject_modal' type="button" class="btn btn-danger btn-sm btn-flat">Reject</button>                  
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
                <div class="col-sm-6 text-right">
                    <a :href="'h3/h3_md_do_revisi/cetak?id=' + do_revisi.id" class="btn btn-flat btn-sm btn-info">Print DO</a>
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
            do_revisi: <?= json_encode($do_revisi) ?>,
            items: <?= json_encode($items) ?>,
        },
        methods: {
            approve: function(status){
                this.loading = true;
                post = _.pick(this.do_revisi, ['id']);
                post.total = this.total;

                axios.post("h3/h3_md_do_revisi/approve", Qs.stringify(post))
                .then(function(res){ 
                    data = res.data;

                    if(data.redirect_url != null){
                        window.location = data.redirect_url;
                    }
                })
                .catch(function(error){
                    app.loading = false;

                    data = error.response.data;
                    toastr.error(data.message);
                });
            },
            reject: function(status){
                this.loading = true;
                post = _.pick(this.do_revisi, ['id']);
                post.alasan_reject = $('#alasan_reject').val();

                axios.post("h3/h3_md_do_revisi/reject", Qs.stringify(post))
                .then(function(res){  
                    data = res.data;

                    if(data.redirect_url != null){
                        window.location = data.redirect_url;
                    }
                })
                .catch(function(err){ 
                    app.loading = false;

                    data = error.response.data;
                    toastr.error(data.message);
                });
            },
            hitung_dpp: function(part){
                if(part.include_ppn == 1){
                return part.harga/1.1;
                }
                return part.harga;
            },
            harga_setelah_diskon: function(part){
                harga_setelah_diskon = part.harga_jual;
                diskon_satuan_dealer = this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, harga_setelah_diskon);
                
                harga_setelah_diskon -= diskon_satuan_dealer;

                if(part.additional_discount == 1){
                    diskon_campaign = this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, harga_setelah_diskon);
                    harga_setelah_diskon -= diskon_campaign;
                }else{
                    diskon_campaign = this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga_jual);
                    harga_setelah_diskon -= diskon_campaign;
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
                return this.harga_setelah_diskon(part) * part.qty_revisi;
            },
            get_nomor_do: function(id_do_sales_order, sudah_revisi){
                if(sudah_revisi == 1){
                    id_do_sales_order += '-REV';
                }
                return id_do_sales_order;
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
                return this.do_revisi.kategori_po == 'KPB';
            },
            table_colspan: function(){
                colspan = 10;

                if(this.kategori_kpb){
                    colspan += 1;
                }

                return colspan;
            },
            sub_total: function(){
                amount_fn = this.amount;
                return _.chain(this.items)
                .sumBy(function(part){
                    return amount_fn(part);
                })
                .value();
            },
            total_diskon: function(){
                return this.do_revisi.diskon_insentif_revisi + (this.do_revisi.diskon_cashback_revisi + this.do_revisi.diskon_cashback_otomatis_revisi);
            },
            total: function(){
                return this.sub_total - this.total_diskon;
            },
            sisa_plafon: function(){
              return this.do_revisi.plafon - this.do_revisi.plafon_booking;
            }
        }
    });
</script>