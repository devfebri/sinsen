<!-- Modal -->
<div id="summary_stock_opname" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Summary Stock Opname</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed table-striped">
                    <tr>
                        <td width='3%'>No.</td>
                        <td>ID Part</td>
                        <td>Nama Part</td>
                        <td>Kuantitas Sistem</td>
                        <td>Kuantitas Real</td>
                        <td>Selisih Kuantitas</td>
                    </tr>
                    <tr v-if='summary.length > 0' v-for='(e, index) in summary'>
                        <td width='3%'>{{ index + 1 }}.</td>
                        <td>{{ e.id_part }}</td>
                        <td>{{ e.nama_part }}</td>
                        <td>{{ e.stock }}</td>
                        <td>{{ e.stock_aktual }}</td>
                        <td>{{ Math.abs(Number(e.stock) - Number(e.stock_aktual)) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>