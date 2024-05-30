<!-- Modal -->
<div id="gudang_stock_opname" class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="gudang_stock_opname_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>ID Gudang</th>
                            <th>Deskripsi</th>
                            <th>Kategori</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        gudang_stock_opname_datatable = $('#gudang_stock_opname_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: false,
                            ajax: {
                                url: "<?=base_url('api/dealer/gudang_stock_opname') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    return d;
                                },
                                type: "POST",
                            },
                            columns: [
                                { data: 'id_gudang' },
                                { data: 'deskripsi_gudang'},
                                { data: 'kategori'},
                                { data: 'action'}
                            ],

                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>