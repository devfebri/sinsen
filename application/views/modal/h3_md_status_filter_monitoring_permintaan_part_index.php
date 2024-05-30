<!-- Modal -->
<div id="h3_md_status_filter_monitoring_permintaan_part_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Status</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_status_filter_monitoring_permintaan_part_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <tr>
                        <td>Draft</td>
                        <td width='3%'>
                            <input type="checkbox" value='Draft' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Approved</td>
                        <td width='3%'>
                            <input type="checkbox" value='Approved' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Submitted</td>
                        <td width='3%'>
                            <input type="checkbox" value='Submitted' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Processed by MD</td>
                        <td width='3%'>
                            <input type="checkbox" value='Processed by MD' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Closed</td>
                        <td width='3%'>
                            <input type="checkbox" value='Closed' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Rejected</td>
                        <td width='3%'>
                            <input type="checkbox" value='Rejected' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Canceled</td>
                        <td width='3%'>
                            <input type="checkbox" value='Canceled' v-model='filters'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>