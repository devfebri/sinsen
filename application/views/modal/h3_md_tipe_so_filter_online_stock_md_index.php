<!-- Modal -->
<div id="h3_md_tipe_so_filter_online_stock_md_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Filter Tipe SO</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed" style="width: 100%">
                    <tr>
                        <td>HLO</td>
                        <td width='3%'>
                            <input type="checkbox" value='HLO' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>URG</td>
                        <td width='3%'>
                            <input type="checkbox" value='URG' v-model='filters'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>