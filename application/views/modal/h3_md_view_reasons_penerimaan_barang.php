<!-- Modal -->
<div id="h3_md_view_reasons_penerimaan_barang" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Reason</h4>
            </div>
            <div class="modal-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#non_kualitas" data-toggle="tab" aria-expanded="true">Non Kualitas</a></li>
                        <li class=""><a href="#kualitas" data-toggle="tab" aria-expanded="false">Kualitas</a></li>
                        <li class=""><a href="#claim_ekspedisi" data-toggle="tab" aria-expanded="false">Claim Ekpedisi</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="non_kualitas">
                            <table class="table table-condensed">
                                <tr>
                                    <td width='5%'>Kode</td>
                                    <td>Claim Non Kualitas</td>
                                    <td class='text-center' width='5%'>Action</td>
                                    <td width='10%'>Qty</td>
                                    <td>Keterangan</td>
                                </tr>
                                <tr v-if='reasons.length > 0 && reason.tipe_claim == "Non Kualitas"' v-for='(reason, index) of reasons'>
                                    <td width='5%'>{{ reason.kode_claim }}</td>
                                    <td>{{ reason.nama_claim }}</td>
                                    <td class='align-middle'>
                                        <input :disabled='(mode == "detail" || parts[index_part].tersimpan == 1) && parts[index_part].edit == 0' type="checkbox" true-value='1' false-value='0' v-model='reason.checked'>
                                    </td>
                                    <td>
                                        <vue-numeric :readonly='(reason.checked == 0 || mode == "detail" || parts[index_part].tersimpan == 1) && parts[index_part].edit == 0' class="form-control" separator='.' v-model='reason.qty'></vue-numeric>
                                    </td>
                                    <td>
                                        <input :readonly='(reason.checked == 0 || mode == "detail" || parts[index_part].tersimpan == 1) && parts[index_part].edit == 0' type="text" class="form-control" v-model='reason.keterangan'>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="kualitas">
                            <table class="table table-condensed">
                                <tr>
                                    <td width='5%'>Kode</td>
                                    <td>Claim Kualitas</td>
                                    <td class='text-center' width='5%'>Action</td>
                                    <td width='10%'>Qty</td>
                                    <td>Keterangan</td>
                                </tr>
                                <tr v-if='reasons.length > 0 && reason.tipe_claim == "Kualitas"' v-for='(reason, index) of reasons'>
                                    <td width='5%'>{{ reason.kode_claim }}</td>
                                    <td>{{ reason.nama_claim }}</td>
                                    <td class='align-middle'>
                                        <input :disabled='(mode == "detail" || parts[index_part].tersimpan == 1) && parts[index_part].edit == 0' type="checkbox" true-value='1' false-value='0' v-model='reason.checked'>
                                    </td>
                                    <td>
                                        <vue-numeric :readonly='(reason.checked == 0 || mode == "detail" || parts[index_part].tersimpan == 1) && parts[index_part].edit == 0' class="form-control" separator='.' v-model='reason.qty'></vue-numeric>
                                    </td>
                                    <td>
                                        <input :readonly='(reason.checked == 0 || mode == "detail" || parts[index_part].tersimpan == 1) && parts[index_part].edit == 0' type="text" class="form-control" v-model='reason.keterangan'>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane" id="claim_ekspedisi">
                            <table class="table table-condensed">
                                <tr>
                                    <td width='5%'>Kode</td>
                                    <td>Claim Ekpedisi</td>
                                    <td class='text-center' width='5%'>Action</td>
                                    <td width='10%'>Qty</td>
                                    <td>Keterangan</td>
                                </tr>
                                <tr v-if='reasons.length > 0 && reason.tipe_claim == "Claim Ekspedisi"' v-for='(reason, index) of reasons'>
                                    <td width='5%'>{{ reason.kode_claim }}</td>
                                    <td>{{ reason.nama_claim }}</td>
                                    <td class='align-middle'>
                                        <input :disabled='(mode == "detail" || parts[index_part].tersimpan == 1) && parts[index_part].edit == 0' type="checkbox" true-value='1' false-value='0' v-model='reason.checked'>
                                    </td>
                                    <td>
                                        <vue-numeric :readonly='(reason.checked == 0 || mode == "detail" || parts[index_part].tersimpan == 1) && parts[index_part].edit == 0' class="form-control" separator='.' v-model='reason.qty'></vue-numeric>
                                    </td>
                                    <td>
                                        <input :readonly='(reason.checked == 0 || mode == "detail" || parts[index_part].tersimpan == 1) && parts[index_part].edit == 0' type="text" class="form-control" v-model='reason.keterangan'>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#h3_md_view_reasons_penerimaan_barang').on('hidden.bs.modal', function (e) {
            form_.parts[form_.index_part].reasons = form_.reasons;
            form_.reasons = [];
        });
    });
</script>
