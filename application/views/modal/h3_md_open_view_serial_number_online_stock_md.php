<!-- Modal -->
<div id="h3_md_open_view_serial_number_online_stock_md" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Serial Number</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td width='3%'>No.</td>
                        <td>FIFO MD</td>
                        <td>Serial Number</td>
                    </tr>
                    <tr v-if='serial_number.length > 0' v-for='(each, index) of serial_number'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ each.fifo }}</td>
                        <td>{{ each.serial_number }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    h3_md_open_view_serial_number_online_stock_md = new Vue({
        el: '#h3_md_open_view_serial_number_online_stock_md',
        data: {
            serial_number: []
        },
        methods: {
            get_serial_number: function(id_part){
                axios.get('h3/h3_md_online_stock_part_md/get_serial_number', {
                    params: {
                        id_part: id_part
                    }
                })
                .then(function(res){
                    h3_md_open_view_serial_number_online_stock_md.serial_number = res.data;
                })
                .catch(function(err){
                    toastr.error(err);
                });
            }
        }
    });
</script>