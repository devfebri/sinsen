<!-- Modal -->
<div id="h3_md_open_view_tipe_motor_online_stock_md" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Tipe Motor</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td width='3%'>No.</td>
                        <td>Tipe Produksi</td>
                        <td>Tipe Marketing</td>
                        <td>Nama Motor</td>
                    </tr>
                    <tr v-if='tipe_motor.length > 0' v-for='(each, index) of tipe_motor'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ each.tipe_produksi }}</td>
                        <td>{{ each.tipe_marketing }}</td>
                        <td>{{ each.deskripsi }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    h3_md_open_view_tipe_motor_online_stock_md = new Vue({
        el: '#h3_md_open_view_tipe_motor_online_stock_md',
        data: {
            tipe_motor: []
        },
        methods: {
            get_tipe_motor: function(id_part){
                axios.get('h3/h3_md_online_stock_part_md/get_tipe_motor', {
                    params: {
                        id_part: id_part
                    }
                })
                .then(function(res){
                    h3_md_open_view_tipe_motor_online_stock_md.tipe_motor = res.data;
                })
                .catch(function(err){
                    toastr.error(err);
                });
            }
        }
    });
</script>