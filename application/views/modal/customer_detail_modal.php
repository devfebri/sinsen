<!-- Modal -->
<div id="modal-customer-detail" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Customer Detail</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" :value="sales_order.id_customer" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" :value="sales_order.nama_customer" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Handphone</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" :value="sales_order.no_hp" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" :value="sales_order.no_mesin" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" :value="sales_order.no_rangka" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>