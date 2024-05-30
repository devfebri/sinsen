<!-- Modal -->
<div id="h3_md_open_view_keep_stock_online_stock_md" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Keep Stock</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr v-if='keep_stock.length > 0' v-for='(each, index) of keep_stock'>
                        <td>{{ each.name }}</td>
                        <td>{{ each.value }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    h3_md_open_view_keep_stock_online_stock_md = new Vue({
        el: '#h3_md_open_view_keep_stock_online_stock_md',
        data: {
            keep_stock: []
        },
        methods: {
            get_keep_stock: function(id_part){
                axios.get('h3/h3_md_online_stock_part_md/get_keep_stock', {
                    params: {
                        id_part: id_part
                    }
                })
                .then(function(res){
                    h3_md_open_view_keep_stock_online_stock_md.keep_stock = res.data;
                })
                .catch(function(err){
                    toastr.error(err);
                });
            }
        }
    });
</script>