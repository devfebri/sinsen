<!-- Modal -->
<div id="detail_part_invoice" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Detail Part Invoice</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed table-striped">
                    <tr class='bg-blue-gradient'>
                        <td width='3%'>No.</td>
                        <td width='30%'>Part</td>
                        <td width='10%'>Qty</td>
                        <td width='20%'>HET</td>
                        <td>Diskon</td>
                        <td width='10%'>Value</td>
                    </tr>
                    <tr v-if='invoice_part_detail.length > 0' v-for='(e, index) of invoice_part_detail'>
                        <td class='align-middle'>{{ index + 1 }}.</td>
                        <td class='align-middle'>{{ e.id_part }} - {{ e.nama_part }}</td>
                        <td class='align-middle'>
                            <vue-numeric read-only='true' v-model='e.qty' separator='.' class='form-control'></vue-numeric>
                        </td>
                        <td class='align-middle'>
                            <vue-numeric read-only='true' v-model='e.harga' separator='.' currency='Rp' class='form-control'></vue-numeric>
                        </td>
                        <td class='align-middle'>
                            {{ e.tipe_diskon }}
                        </td>
                        <td class='align-middle'>
                            <vue-numeric read-only='true' v-model='e.diskon_value' separator='.' class='form-control'></vue-numeric>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>