<!-- Modal -->
<div id="h3_md_tipe_penjualan_filter_back_order_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Tipe Penjualan</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_tipe_penjualan_filter_back_order_index_datatable" class="table table-hover table-condensed" style="width: 100%">
                    <tr>
                        <td>Fix</td>
                        <td width='3%'>
                            <input type="checkbox" value='FIX' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Reguler</td>
                        <td width='3%'>
                            <input type="checkbox" value='REG' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Hotline</td>
                        <td width='3%'>
                            <input type="checkbox" value='HLO' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Urgent</td>
                        <td width='3%'>
                            <input type="checkbox" value='URG' v-model='filters'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>