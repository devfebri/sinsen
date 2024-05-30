<!-- Modal -->
<div id="h3_md_diskon_bertingkat_sales_campaign_detail_diskon" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Diskon Bertingkat</h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td width='3%'>No.</td>
                        <td width='10%'>Qty</td>
                        <td width='15%'>Satuan</td>
                        <td>Diskon</td>
                        <td v-if='mode != "detail"' width='3%'></td>
                    </tr>
                    <tr v-if='diskon_bertingkat.length > 0' v-for='(each, index) of diskon_bertingkat'>
                        <td width='3%'>{{ index + 1 }}.</td>
                        <td>
                            <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='each.qty' separator='.'></vue-numeric>
                        </td>
                        <td>
                            <select :disabled='mode == "detail"' v-model='each.satuan' class="form-control">
                                <option value="">-Pilih-</option>
                                <option v-if='sales_campaign.kategori == "Parts" || sales_campaign.kategori == "Acc"' value="Pcs">Pcs</option>
                                <option v-if='sales_campaign.kategori == "Oil"' value="Botol">Botol</option>
                                <option value="Dus">Dus</option>
                            </select>
                        </td>
                        <td>
                            <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='each.diskon_value' separator='.'></vue-numeric>
                        </td>
                        <td v-if='mode != "detail"' width='3%'>
                            <button class="btn btn-flat btn-sm btn-danger" @click.prevent='hapus_diskon_bertingkat(index)'><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                    <tr v-if='diskon_bertingkat.length < 1'>
                        <td colspan='5' class='text-center'>Tidak ada data</td>
                    </tr>
                </table>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 text-right no-padding">
                            <button v-if='mode != "detail"' class="btn btn-flat btn-sm btn-primary" @click.prevent='add_diskon_bertingkat'><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#h3_md_diskon_bertingkat_sales_campaign_detail_diskon').on('hide.bs.modal', function(){
            form_.sales_campaign_detail_diskon[form_.index_detail_diskon].diskon_bertingkat = form_.diskon_bertingkat;
            form_.diskon_bertingkat = [];
        });
    });
</script>