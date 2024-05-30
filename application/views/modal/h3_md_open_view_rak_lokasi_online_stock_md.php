<!-- Modal -->
<div id="h3_md_open_view_rak_lokasi_online_stock_md" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Rak Lokasi</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td width='3%'>No.</td>
                        <td>Kode Lokasi</td>
                        <td>Deskripsi</td>
                        <td>Qty On Hand</td>
                    </tr>
                    <tr v-if='rak_lokasi.length > 0' v-for='(each, index) of rak_lokasi'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ each.kode_lokasi_rak }}</td>
                        <td>{{ each.deskripsi }}</td>
                        <td>{{ each.qty_on_hand }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    h3_md_open_view_rak_lokasi_online_stock_md = new Vue({
        el: '#h3_md_open_view_rak_lokasi_online_stock_md',
        data: {
            rak_lokasi: []
        },
        methods: {
            get_rak_lokasi: function(id_part){
                axios.get('h3/h3_md_online_stock_part_md/get_rak_lokasi', {
                    params: {
                        id_part: id_part
                    }
                })
                .then(function(res){
                    h3_md_open_view_rak_lokasi_online_stock_md.rak_lokasi = res.data;
                })
                .catch(function(err){
                    toastr.error(err);
                });
            }
        }
    });
</script>