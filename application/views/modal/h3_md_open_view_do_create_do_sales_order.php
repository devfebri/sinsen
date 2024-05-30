<!-- Modal -->
<div id="h3_md_open_view_do_create_do_sales_order" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">List Delivery Order</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr v-if='delivery_orders.length > 0' v-for='(delivery_order, index) of delivery_orders'>
                        <td width='3%'>{{ index + 1 }}</td>
                        <td><a target='_blank' :href="'h3/h3_md_do_sales_order_h3/detail?id=' + delivery_order.id_do_sales_order">{{ delivery_order.id_do_sales_order }}</a></td>
                        <td>{{ delivery_order.status }}</td>
                    </tr>
                    <tr v-if='delivery_orders.length < 1'>
                        <td colspan='3' class='text-center'>Tidak ada data.</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>