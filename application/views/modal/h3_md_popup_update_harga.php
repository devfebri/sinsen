<div id="h3_md_popup_update_harga" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Harga</h4>
            </div>
            <div id='popup_update_harga' class="modal-body">
                <div class="box" style='border: 0; box-shadow: none;'>
                    <div v-if='loading' class="overlay">
                        <i class="fa fa-refresh fa-spin text-light-blue"></i>
                    </div>
                    <div class="container-fluid no-padding">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <button :disabled='sudah_update_po_dealer' class="btn btn-flat btn-sm" @click.prevent='set_update_po_dealer'>Purchase Order Dealer <span v-if='sudah_update_po_dealer' class='text-bold'>(Done)</span></button>
                            </div>
                            <div class="col-md-3 text-center">
                                <button :disabled='sudah_update_so || !sudah_update_po_dealer' class="btn btn-flat btn-sm" @click.prevent='set_update_so'>Sales Order MD <span v-if='sudah_update_so' class='text-bold'>(Done)</span></button>
                            </div>
                            <div class="col-md-3 text-center">
                                <button :disabled='sudah_update_do || !sudah_update_po_dealer || !sudah_update_so' class="btn btn-flat btn-sm" @click.prevent='set_update_do'>Delivery Order MD <span v-if='sudah_update_do' class='text-bold'>(Done)</span></button>
                            </div>
                            <div class="col-md-3 text-center">
                                <button :disabled='sudah_update_po_md || !sudah_update_po_dealer || !sudah_update_so || !sudah_update_do' class="btn btn-flat btn-sm" @click.prevent='set_update_po_md'>Purchase Order MD <span v-if='sudah_update_po_md' class='text-bold'>(Done)</span></button>
                            </div>
                        </div>
                        <div class="row" style='margin-top: 20px;'>
                            <div class="col-md-3 text-center">
                                <button :disabled='sudah_update_niguri || !sudah_update_po_dealer || !sudah_update_so || !sudah_update_do || !sudah_update_po_md' class="btn btn-flat btn-sm" @click.prevent='set_update_niguri'>Niguri MD <span v-if='sudah_update_niguri' clas='text-bold'>(Done)</span></button>
                            </div>
                            <div class="col-md-3 text-center">
                                <button :disabled='sudah_update_do_revisi || !sudah_update_po_dealer || !sudah_update_so || !sudah_update_do || !sudah_update_po_md || !sudah_update_niguri' class="btn btn-flat btn-sm" @click.prevent='set_update_do_revisi'>DO Revisi <span v-if='sudah_update_do_revisi' clas='text-bold'>(Done)</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    popup_update_harga = new Vue({
        el: '#popup_update_harga',
        data: {
            loading: false,
            update_po_dealer: 0,
            update_so: 0,
            update_do: 0,
            update_po_md: 0,
            update_do_revisi: 0,
            update_niguri: 0,
        },
        mounted: function(){
            this.get_update_harga_state();
        },
        methods: {
            get_update_harga_state: function(){
                this.loading = true;
                axios.get('<?= base_url('h3/h3_md_update_harga/state') ?>')
                .then(function(res){
                    data = res.data;

                    if(data != null){
                        popup_update_harga.update_po_dealer = parseInt(data.update_po_dealer);
                        popup_update_harga.update_so = parseInt(data.update_so);
                        popup_update_harga.update_do = parseInt(data.update_do);
                        popup_update_harga.update_po_md = parseInt(data.update_po_md);
                        popup_update_harga.update_do_revisi = parseInt(data.update_do_revisi);
                        popup_update_harga.update_niguri = parseInt(data.update_niguri);
                    }else{
                        $('#h3_md_popup_update_harga').modal('hide');
                    }

                    popup_update_harga.loading = false;
                })
                .catch(function(err){
                    data = err.response.data;
                    toastr.error(data.message);
                });
            },
            set_update_po_dealer: function(){
                this.loading = true;
                axios.get('<?= base_url('h3/h3_md_update_harga/update_po_dealer') ?>')
                .then(function(res){
                    data = res.data;
                    toastr.success(data.message);
                    popup_update_harga.get_update_harga_state();
                })
                .catch(function(err){
                    data = err.response.data;
                    toastr.message(data.message);
                })
                .then(function(){
                    popup_update_harga.loading = false;
                });
            },
            set_update_so: function(){
                this.loading = true;
                axios.get('<?= base_url('h3/h3_md_update_harga/update_so') ?>')
                .then(function(res){
                    data = res.data;
                    toastr.success(data.message);
                    popup_update_harga.get_update_harga_state();
                })
                .catch(function(err){
                    data = err.response.data;
                    toastr.message(data.message);
                })
                .then(function(){
                    popup_update_harga.loading = false;
                });
            },
            set_update_do: function(){
                this.loading = true;
                axios.get('<?= base_url('h3/h3_md_update_harga/update_do') ?>')
                .then(function(res){
                    data = res.data;
                    toastr.success(data.message);
                    popup_update_harga.get_update_harga_state();
                })
                .catch(function(err){
                    data = err.response.data;
                    toastr.message(data.message);
                })
                .then(function(){
                    popup_update_harga.loading = false;
                });
            },
            set_update_po_md: function(){
                this.loading = true;
                axios.get('<?= base_url('h3/h3_md_update_harga/update_po_md') ?>')
                .then(function(res){
                    data = res.data;
                    toastr.success(data.message);
                    popup_update_harga.get_update_harga_state();
                })
                .catch(function(err){
                    data = err.response.data;
                    toastr.message(data.message);
                })
                .then(function(){
                    popup_update_harga.loading = false;
                });
            },
            set_update_niguri: function(){
                this.loading = true;
                axios.get('<?= base_url('h3/h3_md_update_harga/update_niguri') ?>')
                .then(function(res){
                    data = res.data;
                    toastr.success(data.message);
                    popup_update_harga.get_update_harga_state();
                })
                .catch(function(err){
                    data = err.response.data;
                    toastr.message(data.message);
                })
                .then(function(){
                    popup_update_harga.loading = false;
                });
            },
            set_update_do_revisi: function(){
                this.loading = true;
                axios.get('<?= base_url('h3/h3_md_update_harga/update_do_revisi') ?>')
                .then(function(res){
                    data = res.data;
                    toastr.success(data.message);
                    popup_update_harga.get_update_harga_state();
                })
                .catch(function(err){
                    data = err.response.data;
                    toastr.message(data.message);
                })
                .then(function(){
                    popup_update_harga.loading = false;
                });
            }
        },
        computed: {
            sudah_update_po_dealer: function(){
                return this.update_po_dealer == 1;
            },
            sudah_update_so: function(){
                return this.update_so == 1;
            },
            sudah_update_do: function(){
                return this.update_do == 1;
            },
            sudah_update_po_md: function(){
                return this.update_po_md == 1;
            },
            sudah_update_do_revisi: function(){
                return this.update_do_revisi == 1;
            },
            sudah_update_niguri: function(){
                return this.update_niguri == 1;
            },
        },  
        watch: {
            update_do_revisi: function(data){
                if(data == 1){
                    $('#h3_md_popup_update_harga').modal('hide');
                }
            }
        }
    })
</script>