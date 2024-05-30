<div class="container-fluid">
<div class="row">
    <div class="col-sm-2">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="" class="control-label">Nama Customer</label>
                <input type="text" class="form-control" v-model='filter_nama_customer'>
            </div>
        </form>
    </div>
</div>
</div>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-condensed table-hover">
            <tr>
                <td width="3%">No.</td>
                <td>Kode Dealer</td>
                <td>Nama Dealer</td>
                <td>Alamat</td>
                <td>Kota / Kabupaten</td>
                <td width='5%'>Range</td>
                <td v-if="mode != 'detail'" width="3%"></td>
            </tr>
            <tr v-for="(dealer, index) in filtered_dealers">
                <td class="align-middle">{{ index + 1 }}.</td>
                <td class="align-middle">{{ dealer.kode_dealer_md }}</td>
                <td class="align-middle">{{ dealer.nama_dealer }}</td>
                <td class="align-middle">{{ dealer.alamat }}</td>
                <td class="align-middle">{{ dealer.kabupaten }}</td>
                <td class="align-middle text-center">
                    <button class="btn btn-flat btn-sm btn-info" @click.prevent='open_range_model(index)'><i class="fa fa-eye"></i></button>
                </td>
                <td v-if="mode != 'detail'" @click="hapus_dealer(index)" class="text-right align-middle">
                    <button class="btn btn-flat btn-sm btn-danger" type="button"><i class="fa fa-trash-o"></i></button>
                </td>
            </tr>
            <tr v-if="filtered_dealers.length < 1">
                <td v-bind:class="{ 'bg-red' : errors.dealers != null }" class="text-center" colspan="5">Tidak ada data</td>
            </tr>
            <tr>
                <td colspan="6"></td>
                <td>
                    <button v-if="mode != 'detail'" data-toggle='modal' data-target='#h3_dealer_diskon_oli_reguler' class="btn btn-flat btn-primary btn-sm" type="button"><i class="fa fa-plus"></i></button>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php $this->load->view('modal/h3_dealer_diskon_oli_reguler') ?>
<script>
function pilih_dealer_diskon_oli_reguler(data){
    data.ranges = [];
    app.dealers.push(data);
}
</script>
<?php $this->load->view('modal/h3_md_ranges_diskon_oli_reguler'); ?>