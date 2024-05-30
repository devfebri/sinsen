<div class="container-fluid bg-primary" style='padding: 7px 0px;'>
    <div class="row">
        <div class="col-sm-12 text-center">
            <span class="text-bold">Detail Gimmick Global</span>
        </div>
    </div>
</div>
<table class="table table-compact">
    <tr>
        <td width='3%'>No.</td>
        <td width='15%'>Paket Pembelian</td>
        <td width='10%'>Qty</td>
        <td width='15%'>Satuan</td>
        <td width='10%'>Hadiah Part</td>
        <td width='20%'>Nama Hadiah</td>
        <td width='10%'>Qty Hadiah</td>
        <td width='15%'>Satuan</td>
        <td v-if='mode != "detail"' width='3%'></td>
    </tr>
    <tr v-if='sales_campaign_detail_gimmick_global.length > 0' v-for='(each, index) of sales_campaign_detail_gimmick_global'>
        <td>{{ index + 1 }}.</td>
        <td>
            <input :disabled='mode == "detail"' type="text" class="form-control" v-model='each.nama_paket'>
        </td>
        <td>
            <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='each.qty'></vue-numeric>
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
            <input :readonly='each.hadiah_part == 1 || mode == "detail"' type="text" class="form-control" @click.prevent='open_part_hadiah_modal_for_global(index)' v-model='each.nama_hadiah'>
        </td>
        <td>
            <vue-numeric :disabled='mode == "detail"' class="form-control" v-model='each.qty_hadiah'></vue-numeric>
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
            <button class="btn btn-flat btn-danger" @click.prevent='hapus_sales_campaign_detail_gimmick_global(index)'><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <tr v-if='sales_campaign_detail_gimmick_global.length < 1'>
        <td colspan='7' class='text-center'>Tidak ada data.</td>
    </tr>
</table>
<div class="row" style='margin-bottom: 20px;'>
    <div class="col-sm-12 text-right">
        <button v-if='mode != "detail"' class="btn btn-flat btn-primary" @click.prevent='add_sales_campaign_detail_gimmick_global'><i class="fa fa-plus"></i></button>
    </div>
</div>