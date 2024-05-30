<div class="container-fluid bg-blue-gradient">
    <div class="row">
        <div class="col-sm-12 text-center" style='padding: 8px 0px;'>
            <span class='text-bold'>Detail Hadiah</span>
        </div>
    </div>
</div>
<table class="table table-compact">
    <tr>
        <td width='3%'>No.</td>
        <td>Nama Paket</td>
        <td width='15%'>Jumlah Poin</td>
        <td width='8%'>Voucher Rupiah</td>
        <td width='15%'>Nilai Voucher Hadiah</td>
        <td v-if='mode != "detail"' width='3%'></td>
    </tr>
    <tr v-if='sales_campaign_detail_hadiah.length > 0' v-for='(detail_hadiah, index) of sales_campaign_detail_hadiah'>
        <td>{{ index + 1 }}.</td>
        <td>
            <input :disabled='mode == "detail"' type="text" class="form-control" v-model='detail_hadiah.nama_paket'>
        </td>
        <td>
            <vue-numeric :read-only='mode == "detail"' class="form-control" v-model='detail_hadiah.jumlah_poin' separator='.' :min='1'/>
        </td>
        <td class='align-middle text-center'>
            <input :disabled='mode == "detail"' type="checkbox" v-model='detail_hadiah.voucher_rupiah' true-value='1' false-value='0'>
        </td>
        <td>
            <input v-if='detail_hadiah.voucher_rupiah == 0' :disabled='mode == "detail"' type="text" class="form-control" v-model='detail_hadiah.nama_hadiah'>
            <vue-numeric v-if='detail_hadiah.voucher_rupiah == 1' class="form-control" :disabled='mode == "detail"' v-model='detail_hadiah.nama_hadiah' currency='Rp' separator='.'></vue-numeric>
        </td>
        <td v-if='mode != "detail"' class='text-center'>
            <button class="btn btn-flat btn-danger" @click.prevent='hapus_detail_hadiah(index)'><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>
    <tr v-if='sales_campaign_detail_hadiah.length < 1'>
        <td colspan='6' class='text-center'>Tidak ada data.</td>
    </tr>
    <tr>
        <td colspan='5'></td>
        <td>
        </td>
    </tr>
</table>
<div class="container-fluid" style='margin-bottom: 10px;'>
    <div class="row">
        <div class="col-sm-12 text-right">
            <button v-if='mode != "detail"' class="btn btn-flat btn-primary margin" type='button' @click.prevent='add_sales_campaign_detail_hadiah'><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>