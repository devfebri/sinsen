<!-- Modal -->
<div id="h3_md_jenis_po_filter_monitoring_kerja_picker_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Tipe Customer</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_jenis_po_filter_monitoring_kerja_picker_index_datatable" class="table table-bordered table-hover table-condensed" style="width: 100%">
                    <tr>
                        <td>Reguler</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='REG'>
                        </td>
                    </tr>
                    <tr>
                        <td>Fixed</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='FIX'>
                        </td>
                    </tr>
                    <tr>
                        <td>Hotline</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='HLO'>
                        </td>
                    </tr>
                    <tr>
                        <td>Urgent</td>
                        <td width='3%'>
                            <input type="checkbox" v-model='filters' value='URG'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>