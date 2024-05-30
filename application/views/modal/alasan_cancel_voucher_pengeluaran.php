<!-- Modal -->
<div id="alasan_cancel_voucher_pengeluaran" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title text-left" id="myModalLabel">Alasan Cancel</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea class="form-control" id="alasan_cancel" rows='5'></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button @click.prevent='cancel' class="btn btn-flat btn-sm btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>