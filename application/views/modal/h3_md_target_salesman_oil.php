<div v-if='target_salesman.jenis_target_salesman == "Oil"'>
    <table class="table table-condensed table-bordered">
        <tr>
            <td class='align-middle text-center' rowspan='3' width="3%">No.</td>
            <td class='align-middle text-center' rowspan='3'>Kode Customer</td>
            <td class='align-middle text-center' rowspan='3'>Nama Customer</td>
            <td class='align-middle text-center' rowspan='3'>Alamat</td>
            <td class='align-middle text-center' rowspan='3'>Kota / Kabupaten</td>
            <td class='align-middle text-center' colspan='4'>Target Oli</td>
            <td class='align-middle text-center' colspan='2' rowspan='2'>Total</td>
            <td v-if='mode != "detail"' rowspan='3' width='3%'></td>
        </tr>
        <tr>
            <td class='align-middle text-center' colspan='2'>Engine Oil (Oil)</td>
            <td class='align-middle text-center' colspan='2'>Gear Oil (GMO)</td>
        </tr>
        <tr>
            <td width='15%' class='text-center'>Amount</td>
            <td width='8%' class='text-center'>Botol</td>
            <td width='15%' class='text-center'>Amount</td>
            <td width='8%' class='text-center'>Botol</td>
            <td width='15%' class='text-center'>Amount</td>
            <td width='8%' class='text-center'>Botol</td>
        </tr>
        <tr v-if="target_salesman_oils.length > 0" v-for="(target_salesman_oil, index) of filtered_target_salesman_oils">
            <td class='align-middle'>{{ index + 1 }}.</td>
            <td class='align-middle text-center'>{{ target_salesman_oil.kode_dealer_md }}</td>
            <td class='align-middle'>{{ target_salesman_oil.nama_dealer }}</td>
            <td class='align-middle'>{{ target_salesman_oil.alamat }}</td>
            <td class='align-middle'>{{ target_salesman_oil.kabupaten }}</td>
            <td class='text-right'>
                <vue-numeric :read-only='mode == "detail"' class="form-control" currency="Rp" separator="." v-model='target_salesman_oil.amount_engine_oil'></vue-numeric>
            </td>
            <td class='text-center'>
                <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." v-model='target_salesman_oil.botol_engine_oil'></vue-numeric>
            </td>
            <td class='text-right'>
                <vue-numeric :read-only='mode == "detail"' class="form-control" currency="Rp" separator="." v-model='target_salesman_oil.amount_gear_oil'></vue-numeric>
            </td>
            <td class='text-center'>
                <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." v-model='target_salesman_oil.botol_gear_oil'></vue-numeric>
            </td>
            <td class='text-right'>
                <vue-numeric :read-only='mode == "detail"' read-only class="form-control" currency='Rp' separator="." v-model='hitung_amount_target_salesman_oil(target_salesman_oil)'></vue-numeric>
            </td>
            <td class='text-center'>
                <vue-numeric read-only class="form-control" separator="." v-model='hitung_botol_target_salesman_oil(target_salesman_oil)'></vue-numeric>
            </td>
            <td v-if='mode != "detail"'>
                <button class="btn btn-flat btn-sm btn-danger" @click.prevent='hapus_target_salesman_oils(index)'><i class="fa fa-trash-o"></i></button>
            </td>
        </tr>
        <tr v-if='filtered_target_salesman_oils.length > 0'>
            <td class='text-right' colspan='9'>Total Target</td>
            <td class='text-right'>
                <vue-numeric class="form-control" v-model='target_salesman_channel' currency='Rp' separator='.' read-only></vue-numeric>
            </td>
            <td class='text-center'>
                <vue-numeric class="form-control" v-model='total_botol' separator='.' read-only></vue-numeric>
            </td>
            <td v-if='mode != "detail"'></td>
        </tr>
        <tr v-if="filtered_target_salesman_oils.length < 1">
            <td class="text-center" colspan="10">Tidak ada data</td>
        </tr>
    </table>
    <div v-if='mode != "detail"' class="row">
        <div class="col-sm-12 text-right">
            <button class="btn btn-flat btn-sm btn-primary margin" type='button' data-toggle='modal' data-target='#h3_dealer_target_salesman'><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>