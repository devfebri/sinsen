<!-- Modal -->
<div id="h3_md_status_do_filter_monitoring_do_pending" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Status</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td>On Process</td>
                        <td width='3%'>
                            <input type="checkbox" value='On Process' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Approved</td>
                        <td width='3%'>
                            <input type="checkbox" value='Approved' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Rejected</td>
                        <td width='3%'>
                            <input type="checkbox" value='Rejected' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Picking List</td>
                        <td width='3%'>
                            <input type="checkbox" value='Picking List' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Proses Scan</td>
                        <td width='3%'>
                            <input type="checkbox" value='Proses Scan' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Closed Scan</td>
                        <td width='3%'>
                            <input type="checkbox" value='Closed Scan' v-model='filters'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>