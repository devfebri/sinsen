<div id="parts_manage_stock_out" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="parts_manage_stock_out_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>ID Part</th>
                            <th>Part</th>
                            <th>Gudang</th>
                            <th>Rak</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function(){
                        parts_manage_stock_out_datatable = $('#parts_manage_stock_out_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: false,
                            order: [],
                            ajax: {
                                url: "<?=base_url('api/dealer/parts_manage_stock_out') ?>",
                                dataSrc: "data",
                                data: function ( d ) {
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'id_part' },
                                { data: 'nama_part' },
                                { data: 'id_gudang' },
                                { data: 'id_rak' },
                                { data: 'stock' },
                                { data: 'action' }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>