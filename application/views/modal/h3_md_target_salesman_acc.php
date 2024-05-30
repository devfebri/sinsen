<div v-if='target_salesman.jenis_target_salesman == "Acc"'>
    <table class="table table-condensed">
        <tr>
            <td width="3%">No.</td>
            <td>Kode Customer</td>
            <td>Nama Customer</td>
            <td>Alamat</td>
            <td>Kabupaten</td>
            <td width='20%'>Target Accessories</td>
            <td v-if='mode != "detail"' width="3%"></td>
        </tr>
        <tr v-if="target_salesman_acc.length > 0" v-for="(acc, index) of filtered_target_salesman_acc">
            <td>{{ index + 1 }}.</td>
            <td>{{ acc.kode_dealer_md }}</td>
            <td>{{ acc.nama_dealer }}</td>
            <td>{{ acc.alamat }}</td>
            <td>{{ acc.kabupaten }}</td>
            <td>
                <vue-numeric :read-only='mode == "detail"' class="form-control" currency="Rp" separator="." v-model='acc.target_acc'></vue-numeric>
            </td>
            <td v-if='mode != "detail"'>
                <button class="btn btn-flat btn-sm btn-danger" @click.prevent='hapus_target_salesman_acc(index)'><i class="fa fa-trash-o"></i></button>
            </td>
        </tr>
        <tr v-if="filtered_target_salesman_acc.length < 1">
            <td class="text-center" colspan="5">Tidak ada data</td>
        </tr>
    </table>
    <div v-if='mode != "detail"' class="row">
        <div class="col-sm-12 text-right">
            <button class="btn btn-flat btn-sm btn-primary margin" type='button' data-toggle='modal' data-target='#h3_dealer_target_salesman'><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>