<!-- Modal -->
<div id="h3_md_produk_filter_online_stock_md_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Filter Produk</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed" style="width: 100%">
                    <tr>
                        <td>Parts</td>
                        <td width='3%'>
                            <input type="checkbox" value='Parts' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Oil</td>
                        <td width='3%'>
                            <input type="checkbox" value='Oil' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Accesories</td>
                        <td width='3%'>
                            <input type="checkbox" value='Acc' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Apparel</td>
                        <td width='3%'>
                            <input type="checkbox" value='Apparel' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Tools</td>
                        <td width='3%'>
                            <input type="checkbox" value='Tools' v-model='filters'>
                        </td>
                    </tr>
                    <tr>
                        <td>Other</td>
                        <td width='3%'>
                            <input type="checkbox" value='Other' v-model='filters'>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>