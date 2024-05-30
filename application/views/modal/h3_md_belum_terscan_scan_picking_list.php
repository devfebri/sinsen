<!-- Modal -->
<div id="h3_md_belum_terscan_scan_picking_list" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Part yang belum terscan</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tr>
                        <td>No.</td>
                        <td>Part Number</td>
                        <td>Part Deskripsi</td>
                        <td>Qty DO</td>
                        <td>Qty Picking</td>
                        <td>Qty Sudah Scan</td>
                        <td>Qty Belum Scan</td>
                    </tr>
                    <tr v-if='parts_belum_scan.length > 0' v-for='(each, index) of parts_belum_scan'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ each.id_part }}</td>
                        <td>{{ each.nama_part }}</td>
                        <td>{{ each.qty_do }}</td>
                        <td>{{ each.qty_picking }}</td>
                        <td>{{ each.qty_sudah_scan }}</td>
                        <td>{{ each.qty_belum_scan }}</td>
                    </tr>
                </table>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-flat btn-sm btn-warning" @click.prevent='selesai_scan'>Scan Selesai</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>