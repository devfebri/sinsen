<!-- Modal -->
<div id="h3_md_status_filter_sales_order_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <td>New SO</td>
                        <td width='3%'>
                            <input type="checkbox" value='New SO' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>New SO BO</td>
                        <td width='3%'>
                            <input type="checkbox" value='New SO BO' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>On Process</td>
                        <td width='3%'>
                            <input type="checkbox" value='On Process' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Barang Bagi</td>
                        <td width='3%'>
                            <input type="checkbox" value='Barang Bagi' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Canceled</td>
                        <td width='3%'>
                            <input type="checkbox" value='Canceled' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Back Order</td>
                        <td width='3%'>
                            <input type="checkbox" value='Back Order' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Closed</td>
                        <td width='3%'>
                            <input type="checkbox" value='Closed' v-model='filters'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>