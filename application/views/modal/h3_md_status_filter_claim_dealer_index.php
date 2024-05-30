<!-- Modal -->
<div id="h3_md_status_filter_claim_dealer_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Status</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_status_filter_claim_dealer_index_datatable" class="table table-bordered table-hover table-condensed" style="width: 100%">
                    <tr>
                        <td>Open</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='Open'>
                        </td>
                    </tr>
                    <tr>
                        <td>Approved</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='Approved'>
                        </td>
                    </tr>
                    <tr>
                        <td>On Process</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='On Process'>
                        </td>
                    </tr>
                    <tr>
                        <td>Rejected</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='Rejected'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>