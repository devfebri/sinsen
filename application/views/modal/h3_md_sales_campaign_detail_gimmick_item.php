<!-- Modal -->
<div id="h3_md_sales_campaign_detail_gimmick_item" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Sales Campaign Detail Gimmick Item</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td width='3%'>No.</td>
                        <td widht='5%'>Qty</td>
                        <td width='15%'>Satuan</td>
                        <td width='10%'>Hadiah Part</td>
                        <td>Nama Hadiah</td>
                        <td width='10%'>Qty Hadiah</td>
                        <td width='15%'>Satuan</td>
                        <td v-if='mode != "detail"' width='3%'></td>
                    </tr>
                    <tr v-if='detail_gimmick_item.length > 0' v-for='(each, index) of detail_gimmick_item'>
                        <td class='align-middle'>{{ index + 1 }}.</td>
                        <td>
                            <vue-numeric :disabled='mode == "detail"' v-model='each.qty' class="form-control" separator='.'></vue-numeric>
                        </td>
                        <td>
                            <select :disabled='mode == "detail"' v-model='each.satuan' class="form-control">
                                <option value="">-Pilih-</option>
                                <option v-if='sales_campaign.kategori == "Parts" || sales_campaign.kategori == "Acc"' value="Pcs">Pcs</option>
                                <option v-if='sales_campaign.kategori == "Oil"' value="Botol">Botol</option>
                                <option value="Dus">Dus</option>
                            </select>
                        </td>
                        <td class='text-center align-middle'>
                            <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='each.hadiah_part'>
                        </td>
                        <td>
                            <input :disabled='mode == "detail"' :readonly='each.hadiah_part == 1' type="text" class="form-control" v-model='each.nama_hadiah' @click.prevent='open_part_hadiah_modal(index)'>
                        </td>
                        <td>
                            <vue-numeric :disabled='mode == "detail"' v-model='each.qty_hadiah' class="form-control" separator='.'></vue-numeric>
                        </td>
                        <td>
                            <select :disabled='mode == "detail"' v-model='each.satuan_hadiah' class="form-control">
                                <option value="">-Pilih-</option>
                                <option v-if='sales_campaign.kategori == "Parts" || sales_campaign.kategori == "Acc"' value="Pcs">Pcs</option>
                                <option v-if='sales_campaign.kategori == "Oil"' value="Botol">Botol</option>
                                <option value="Dus">Dus</option>
                            </select>
                        </td>
                        <td v-if='mode != "detail"'>
                            <button class="btn btn-flat btn-danger" @click.prevent='hapus_detail_gimmick_item(index)'><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                    <tr v-if='detail_gimmick_item.length < 1'>
                        <td colspan='6' class='text-center'>Tidak ada data</td>
                    </tr>
                </table>
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' @click.prevent='add_sales_campaign_detail_gimmick_item'><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#h3_md_sales_campaign_detail_gimmick_item').on('hide.bs.modal', function(){
            form_.sales_campaign_detail_gimmick[form_.index_detail_gimmick].detail_gimmick_item = form_.detail_gimmick_item;
            form_.detail_gimmick_item = [];
        });
    });
</script>