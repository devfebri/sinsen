<div v-if='target_salesman.jenis_target_salesman == "Parts"'>
    <table class="table">
        <tr>
            <td width="3%">No.</td>
            <td>Kode Customer</td>
            <td>Nama Customer</td>
            <td>Alamat</td>
            <td>Kabupaten</td>
            <td width='5%'>Global</td>
            <td width='15%'>Target Part</td>
            <td width="3%"></td>
            <td v-if='mode != "detail"' width="3%"></td>
        </tr>
        <tr v-if="target_salesman_parts.length > 0" v-for="(target_salesman_part, index) of filtered_target_salesman_parts">
            <td>{{ index + 1 }}.</td>
            <td>{{ target_salesman_part.kode_dealer_md }}</td>
            <td>{{ target_salesman_part.nama_dealer }}</td>
            <td>{{ target_salesman_part.alamat }}</td>
            <td>{{ target_salesman_part.kabupaten }}</td>
            <td>
                <input type="checkbox" v-model='target_salesman_part.global' true-value='1' false-value='0' :disabled='mode == "detail"'>
            </td>
            <td>
                <vue-numeric v-if='target_salesman_part.global == 1' :read-only='mode == "detail"' class="form-control" currency="Rp" separator="." v-model='target_salesman_part.target_part'></vue-numeric>
                <vue-numeric v-if='target_salesman_part.global == 0' read-only class="form-control" currency="Rp" separator="." v-model='total_target_parts_per_dealer(target_salesman_part)'></vue-numeric>
            </td>
            <td>
                <button v-if='target_salesman_part.global == 0' class="btn btn-flat btn-sm btn-info" @click.prevent='open_target_salesman_parts_items(index)'><i class="fa fa-eye"></i></button>
            </td>
            <td v-if='mode != "detail"'>
                <button class="btn btn-flat btn-sm btn-danger" @click.prevent='hapus_target_salesman_parts(index)'><i class="fa fa-trash-o"></i></button>
            </td>
        </tr>
        <tr v-if='filtered_target_salesman_parts.length > 0'>
            <td class='text-right' colspan='6'>Total Target</td>
            <td>
                <vue-numeric class="form-control" v-model='target_salesman_channel' currency='Rp' separator='.' read-only></vue-numeric>
            </td>
        </tr>
        <tr v-if="filtered_target_salesman_parts.length < 1">
            <td class="text-center" colspan="7">Tidak ada data</td>
        </tr>
    </table>
    <div v-if='mode != "detail"' class="row">
        <div class="col-sm-12 text-right">
            <button class="btn btn-flat btn-sm btn-primary margin" type='button' data-toggle='modal' data-target='#h3_dealer_target_salesman'><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>