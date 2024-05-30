<!-- Modal -->
<div id="h3_md_sales_campaign_detail_cashback_item" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Sales Campaign Detail Cashback Item</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td width='3%'>No.</td>
                        <td width='10%'>Qty</td>
                        <td width='15%'>Satuan</td>
                        <td>Cashback</td>
                        <td v-if='mode != "detail"' width='3%'></td>
                    </tr>
                    <tr v-if='detail_cashback_item.length > 0' v-for='(each_detail_cashback_item, index) of detail_cashback_item'>
                        <td>{{ index + 1 }}.</td>
                        <td>
                            <vue-numeric :disabled='mode == "detail"' v-model='each_detail_cashback_item.qty' class="form-control" separator='.'></vue-numeric>
                        </td>
                        <td>
                            <select :disabled='mode == "detail"' v-model='each_detail_cashback_item.satuan' class="form-control">
                                <option value="">-Pilih-</option>
                                <option v-if='sales_campaign.kategori == "Parts" || sales_campaign.kategori == "Acc"' value="Pcs">Pcs</option>
                                <option v-if='sales_campaign.kategori == "Oil"' value="Botol">Botol</option>
                                <option value="Dus">Dus</option>
                            </select>
                        </td>
                        <td>
                            <vue-numeric :disabled='mode == "detail"' v-model='each_detail_cashback_item.cashback' class="form-control" separator='.' currency='Rp'></vue-numeric>
                        </td>
                        <td v-if='mode != "detail"'>
                            <button class="btn btn-flat btn-danger" @click.prevent='hapus_detail_cashback_item(index)'><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                    <tr v-if='detail_cashback_item.length < 1'>
                        <td colspan='5' class='text-center'>Tidak ada data</td>
                    </tr>
                </table>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' @click.prevent='add_sales_campaign_detail_cashback_item'><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#h3_md_sales_campaign_detail_cashback_item').on('hide.bs.modal', function(){
            form_.sales_campaign_detail_cashback[form_.index_detail_cashback].detail_cashback_item = form_.detail_cashback_item;
            form_.detail_cashback_item = [];
        });
    });
</script>