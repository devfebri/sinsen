<!-- Modal -->
<div id="parts_shipping_list" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="parts_shipping_list_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>ID Part</th>
                            <th>Nama Part</th>
                            <th>Dus</th>
                            <th>Qty Ship</th>
                            <th>Qty Sudah Terima</th>
                            <th>Qty Belum Terima</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        parts_shipping_list_datatable = $('#parts_shipping_list_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/parts_shipping_list') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.id_packing_sheet = form_.packing_sheet.id_packing_sheet;
                                },
                                type: "POST"
                            },
                            columns: [{
                                data: 'id_part'
                            }, {
                                data: 'nama_part'
                            },{
                                data: 'no_dus'
                            },{
                                data: 'qty_ship'
                            },{
                                data: 'qty_sudah_terima'
                            },{
                                data: 'qty_belum_terima'
                            },{
                                data: 'action'
                            }],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>