<div class="container-fluid bg-blue-gradient" style='padding: 7px 0px;'>
    <div class="row">
        <div class="col-sm-12 text-center">
            <span class="text-bold">Detail Diskon Global</span>
        </div>
    </div>
</div>
<table class="table table-compact">
    <tr>
        <td width='3%'>No.</td>
        <td>Paket Pembelian</td>
        <td>Qty</td>
        <td width='15%'>Satuan</td>
        <td width='10%'>Tipe Diskon</td>
        <td width='10%'>Diskon Value</td>
        <td v-if='mode != "detail"' width='3%'></td>
    </tr>
    <tr v-if='sales_campaign_detail_diskon_global.length > 0' v-for='(each, index) of sales_campaign_detail_diskon_global'>
        <td>{{ index + 1 }}.</td>
        <td>
            <input :disabled='mode == "detail"' type="text" class="form-control" v-model='each.nama_paket'>
        </td>
        <td>
            <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='each.qty'></vue-numeric>
        </td>
        <td>
            <select :disabled='mode == "detail"' class="form-control" v-model='each.satuan'>
                <option value="">-Pilih-</option>
                <option v-if='sales_campaign.kategori == "Parts" || sales_campaign.kategori == "Acc"' value="Pcs">Pcs</option>
                <option v-if='sales_campaign.kategori == "Oil"' value="Botol">Botol</option>
                <option value="Dus">Dus</option>
            </select>
        </td>
        <td>
            <select :disabled='mode == "detail"' class="form-control" v-model='each.tipe_diskon'>
                <option value="">-Pilih-</option>
                <option value="Rupiah">Rupiah</option>
                <option value="Persen">Persen</option>
            </select>
        </td>
        <td>
            <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='each.diskon_value'></vue-numeric>
        </td>
        <td v-if='mode != "detail"'>
            <button class="btn btn-flat btn-danger" @click.prevent='hapus_sales_campaign_detail_diskon_global(index)'><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <tr v-if='sales_campaign_detail_diskon.length < 1'>
        <td colspan='7' class='text-center'>Tidak ada data.</td>
    </tr>
</table>
<div class="row" style='margin-bottom: 20px;'>
    <div class="col-sm-12 text-right">
        <button v-if='mode != "detail"' class="btn btn-flat btn-primary" @click.prevent='add_sales_campaign_detail_diskon_global'><i class="fa fa-plus"></i></button>
    </div>
</div>