<!-- Modal -->
<div id="h3_md_tipe_back_order_filter_proses_barang_bagi" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Tipe Back Order</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_tipe_back_order_filter_proses_barang_bagi_datatable" class="table table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <td>Fix</td>
                            <td width='3%'>
                                <input type="checkbox" value='FIX' v-model='filter_tipe_back_order'>
                            </td>
                        </tr>
                        <tr>
                            <td>Reguler</td>
                            <td width='3%'>
                                <input type="checkbox" value='REG' v-model='filter_tipe_back_order'>
                            </td>
                        </tr>
                        <tr>
                            <td>Urgent</td>
                            <td width='3%'>
                                <input type="checkbox" value='URG' v-model='filter_tipe_back_order'>
                            </td>
                        </tr>
                        <tr>
                            <td>Hotline</td>
                            <td width='3%'>
                                <input type="checkbox" value='HLO' v-model='filter_tipe_back_order'>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>