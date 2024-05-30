<!-- Modal -->
<div id="reason_inbound_form" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Reason</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <td>No.</td>
                        <td>Reason</td>
                        <td>Action</td>
                        <td>Qty</td>
                        <td>Keterangan</td>
                        <td>Gudang</td>
                        <td>Rak</td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if='reason_part.length > 0 && index_reason > 0' v-for='(each_reason, index_reason) of reason_part'>
                        <td class='align-middle'>{{ index_reason }}.</td>
                        <td class='align-middle'>{{ each_reason.reason }}</td>
                        <td class='align-middle'>
                            <input v-model='each_reason.action' type="checkbox" true-value='1' false-value='0' :disabled='mode == "detail"'>
                        </td>
                        <td class='align-middle'>
                            <input :disabled='mode == "detail"' v-if='each_reason.action == 1' @change='qty_reason_change(each_reason.reason, $event)' v-model='each_reason.qty' type="text" class="form-control">
                        </td>
                        <td class='align-middle'>
                            <input :disabled='mode == "detail"' v-if='each_reason.action == 1' v-model='each_reason.keterangan' type="text" class="form-control">
                        </td>
                        <td class='align-middle text-center'>
                            <input v-if='each_reason.action == 1 && each_reason.reason == "Kerusakan"' @click.prevent='change_index(index_part, "rak_kerusakan")' v-model='each_reason.id_gudang' type="text" class="form-control" readonly>
                            <button :disabled='mode == "detail"' v-bind:class="{ 'btn-success': each_reason.plus == 1 }" v-if='index_reason > 2' class="btn btn-flat" @click.prevent='button_plus(index_reason)'><i class="fa fa-plus"></i></button>
                            <button :disabled='mode == "detail"' v-bind:class="{ 'btn-danger': each_reason.minus == 1 }" v-if='index_reason > 2' class="btn btn-flat" @click.prevent='button_minus(index_reason)'><i class="fa fa-minus"></i></button>
                        </td>
                        <td class='align-middle'>
                            <input v-if='each_reason.action == 1 && each_reason.reason == "Kerusakan"' @click.prevent='change_index(index_part, "rak_kerusakan")' v-model='each_reason.id_rak' type="text" class="form-control" readonly>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#reason_inbound_form').on('hide.bs.modal', function(){
            form_.parts[form_.index_part].reason = form_.reason_part;
        });
    });
</script>