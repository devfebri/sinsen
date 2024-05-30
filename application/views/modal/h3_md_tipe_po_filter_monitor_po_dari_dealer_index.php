<!-- Modal -->
<div id="h3_md_tipe_po_filter_monitor_po_dari_dealer_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Tipe PO</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_tipe_po_filter_monitor_po_dari_dealer_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <tr>
                        <td>Fix</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='FIX'>
                        </td>
                    </tr>
                    <tr>
                        <td>Reguler</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='REG'>
                        </td>
                    </tr>
                    <tr>
                        <td>Urgent</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='URG'>
                        </td>
                    </tr>
                    <tr>
                        <td>Hotline</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='HLO'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>