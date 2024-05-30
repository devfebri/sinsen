<div id="demand_check_part_stock" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">History Demand</h4>
        </div>
        <div class="modal-body">
            <table class="table table-condesned table-striped">
                <tr class='bg-blue-gradient'>
                    <td class="align-middle" width='3%'>No.</td>
                    <td class="align-middle">Part Number</td>
                    <td class="align-middle">Part Deskripsi</td>
                    <td class="align-middle">Qty</td>
                    <td class="align-middle">HET</td>
                    <td class="align-middle text-right">Sub Total</td>
                </tr>
                <tr v-if='demand_part.length > 0' v-for='(each, index) in demand_part'>
                    <td class="align-middle">{{ index + 1 }}.</td>
                    <td class="align-middle">{{ each.id_part }}</td>
                    <td class="align-middle">{{ each.nama_part }}</td>
                    <td class="align-middle">{{ each.qty }}</td>
                    <td class="align-middle">{{ each.harga_satuan_formatted }}</td>
                    <td class="align-middle text-right">
                        <vue-numeric :read-only='true' v-model='each.harga_satuan * each.qty' separator='.' class='form-control' currency='Rp'></vue-numeric>
                    </td>
                </tr>
                <tr>
                    <td class='align-middle text-right' colspan='5'>Total</td>
                    <td class='align-middle text-right'>
                        <vue-numeric :read-only='true' v-model='total_demand' separator='.' class='form-control' currency='Rp'></vue-numeric>
                    </td>
                </tr>
                <tr v-if='demand_part.length < 1'>
                    <td colspan='6' class='text-center align-middle'>Tidak ada data.</td>
                </tr>
            </table>
        </div>
    </div>
    </div>
</div>