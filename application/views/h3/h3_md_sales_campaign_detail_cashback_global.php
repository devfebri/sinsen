<div>
    <div class="container-fluid bg-primary" style='padding: 7px 0px;'>
        <div class="row">
            <div class="col-sm-12 text-center">
                <span class="text-bold">Detail Cashback Global</span>
            </div>
        </div>
    </div>
    <table class="table table-compact">
        <tr>
            <td width='3%'>No.</td>
            <td>Nama Paket</td>
            <td width='10%'>Qty</td>
            <td width='15%'>Satuan</td>
            <td>Cashback</td>
            <td v-if='mode != "detail"' width='3%'></td>
        </tr>
        <tr v-if='sales_campaign_detail_cashback_global.length > 0' v-for='(detail_cashback_global, index) of sales_campaign_detail_cashback_global'>
            <td>{{ index + 1 }}.</td>
            <td>
                <input :disabled='mode == "detail"' type="text" class="form-control" v-model='detail_cashback_global.nama_paket'>
            </td>
            <td>
                <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='detail_cashback_global.qty' separator='.' :min='1'/>
            </td>
            <td>
                <select :disabled='mode == "detail"' v-model='detail_cashback_global.satuan' class="form-control">
                    <option value="">-Pilih-</option>
                    <option v-if='sales_campaign.kategori == "Parts" || sales_campaign.kategori == "Acc"' value="Pcs">Pcs</option>
                    <option v-if='sales_campaign.kategori == "Oil"' value="Botol">Botol</option>
                    <option value="Dus">Dus</option>
                </select>
            </td>
            <td>
                <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='detail_cashback_global.cashback' separator='.' currency='Rp '/>
            </td>
            <td v-if='mode != "detail"'>
                <button class="btn btn-flat btn-danger" @click.prevent='hapus_detail_cashback_global(index)'><i class="fa fa-trash-o"></i></button>
            </td>
        </tr>
        <tr v-if='sales_campaign_detail_cashback_global.length < 1'>
            <td colspan='5' class='text-center'>Tidak ada data.</td>
        </tr>
    </table>
    <div class="row">
        <div class="col-sm-12 text-right">
            <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' @click.prevent='add_sales_campaign_detail_cashback_global'><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>