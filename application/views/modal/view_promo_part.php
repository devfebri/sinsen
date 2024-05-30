<div id='view_promo_part'  class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Hadiah</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed table-striped">
                    <tr>
                        <td width='3%'>No.</td>
                        <td>Nama</td>
                        <td>Qty</td>
                    </tr>
                    <tr v-if='selected_view_gift_part_promo.length > 0' v-for='(gift, index_gift) of selected_view_gift_part_promo'>
                        <td>{{ index_gift + 1 }}</td>
                        <td>{{ gift.nama_hadiah }}</td>
                        <td>{{ gift.qty_hadiah }}</td>
                    </tr>
                    <tr v-if='selected_view_gift_part_promo.length < 1'>
                        <td colspan='3' class='text-center'>Tidak ada data</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>