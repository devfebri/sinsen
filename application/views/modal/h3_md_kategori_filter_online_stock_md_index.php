<!-- Modal -->
<div id="h3_md_kategori_filter_online_stock_md_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filter Kategori</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed" style="width: 100%">
                    <tr>
                        <td>SIM Part</td>
                        <td width='3%'>
                            <input type="checkbox" value='SIM Part' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Non SIM Part</td>
                        <td width='3%'>
                            <input type="checkbox" value='Non SIM Part' v-model='filters'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>