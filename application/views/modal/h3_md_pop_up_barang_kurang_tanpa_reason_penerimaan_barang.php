<!-- Modal -->
<div id="h3_md_pop_up_barang_kurang_tanpa_reason_penerimaan_barang" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style='width: 80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Kuantitas Barang Tercatat Selisih</h4>
            </div>
            <div class="modal-body">
                <span class="text-bold">Terdapat sisa {{ parts_dengan_reasons_tidak_lengkap.length }} part yang kuantitas selisih dengan kuantitas yang tercatat di reasons berbeda.Mohon lakukan perbaikan sebelum melanjutkan ketahap berikutnya.</span>
                <table style='margin-top: 15px;' class="table table-condesned table-bordered">
                    <tr>
                        <td class='align-middle'>No. Karton</td>
                        <td class='align-middle'>Part Number</td>
                        <td class='align-middle'>Nama Part</td>
                        <td class='align-middle'>Qty PS</td>
                        <td class='align-middle'>Qty Diterima</td>
                        <td class='align-middle'>Qty Selisih</td>
                        <td class='align-middle'>Qty Reasons</td>
                        <td class='align-middle'>Reasons</td>
                    </tr>
                    <tr v-if='parts_dengan_reasons_tidak_lengkap.length > 0' v-for='(each, index) of parts_dengan_reasons_tidak_lengkap'>
                        <td>{{ each.nomor_karton }}</td>
                        <td>{{ each.id_part }}</td>
                        <td>{{ each.nama_part }}</td>
                        <td>{{ each.packing_sheet_quantity }}</td>
                        <td>{{ each.qty_diterima }}</td>
                        <td>{{ hitung_qty_plus_minus(each) }}</td>
                        <td>{{ get_sum_of_qty_reasons(each.reasons) }}</td>
                        <td>
                            <button class="btn btn-flat btn-sm btn-info" @click.prevent='open_reason(each)'><i class="fa fa-eye"></i></button>
                        </td>
                    </tr>
                    <tr v-if='parts_dengan_reasons_tidak_lengkap.length < 1'>
                        <td colspan='8' class='text-center'>Tidak ada data</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>