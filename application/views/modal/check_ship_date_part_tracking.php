<!-- Modal -->
<div id="check_ship_date_part_tracking" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Ship Date</h4>
            </div>
            <div class="modal-body">
                <h5>Part Number: <span class="text-bold">{{ id_part_ship_dates }}</span></h5>
                <h5>Part Description: <span class="text-bold">{{ nama_part_ship_dates }}</span></h5>
                <table class="table table-condensed">
                    <tr class='bg-blue-gradient'>
                        <td width='3%'>No.</td>
                        <td>Ship Number</td>
                        <td>Ship Qty</td>
                        <td>Ship Date</td>
                        <td>Expedition</td>
                        <td>Plat Number</td>
                    </tr>
                    <tr v-if='ship_dates.length > 0' v-for='(each, index) of ship_dates'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ each.ship_number }}</td>
                        <td>{{ each.ship_qty }}</td>
                        <td>{{ each.created_at }}</td>
                        <td>{{ each.ekspedisi }}</td>
                        <td>{{ each.no_plat }}</td>
                    </tr>
                    <tr class='bg-gray'>
                        <td colspan='2' class='text-right text-bold'>Total</td>
                        <td colspan='4' class='text-bold'>{{ total_qty_ship }}</td>
                    </tr>
                    <tr v-if='ship_dates.length < 1'>
                        <td class='text-center' colspan='6'></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>