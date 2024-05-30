<!-- <div v-if='target_salesman.jenis_target_salesman == "Parts" ||target_salesman.jenis_target_salesman == "Oil"'> -->
    <table class="table">
        <tr>
            <td width="3%">No.</td>
            <td>Kode Customer</td>
            <td>Nama Customer</td>
            <td>Alamat</td>
            <td>Kabupaten</td>
            <td width='15%'>Target Sales Out</td>
            <td width="3%"></td>
            <td v-if='mode != "detail"' width="3%"></td>
        </tr>
        <tr v-if="target_dealer_detail.length > 0" v-for="(target_dealer_det, index) of target_dealer_detail">
            <td>{{ index + 1 }}.</td>
            <td>{{ target_dealer_det.kode_dealer_md }} </td>
            <td>{{ target_dealer_det.nama_dealer }}</td>
            <td>{{ target_dealer_det.alamat }}</td>
            <td>{{ target_dealer_det.kabupaten }}</td>
            <td>
                <vue-numeric :read-only='mode == "detail"' class="form-control" currency="Rp" separator="." v-model='target_dealer_det.target_dealer'></vue-numeric>
            </td>
            <td>
                <button v-if='target_dealer_det.global == 0' class="btn btn-flat btn-sm btn-info" @click.prevent='open_target_dealer_detail_items(index)'><i class="fa fa-eye"></i></button>
            </td>
            <td v-if='mode != "detail"'>
                <button class="btn btn-flat btn-sm btn-danger" @click.prevent='hapus_target_dealer_details(index)'><i class="fa fa-trash-o"></i></button>
            </td>
        </tr>
        <tr v-if="filtered_target_dealer_details.length < 1">
            <td class="text-center" colspan="7">Tidak ada data</td>
        </tr>
    </table>
    <div v-if='mode != "detail"' class="row">
        <div class="col-sm-12 text-right">
            <button class="btn btn-flat btn-sm btn-primary margin" type='button' data-toggle='modal' data-target='#h3_dealer_target_sales_out'><i class="fa fa-plus"></i></button>
        </div>
    </div>
<!-- </div> -->