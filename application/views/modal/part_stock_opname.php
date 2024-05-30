<!-- Modal -->
<div id="part_stock_opname" class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="part_stock_opname_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>ID Part</th>
                            <th>Part</th>
                            <th>Gudang</th>
                            <th>Rak</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        part_stock_opname_datatable = $('#part_stock_opname_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            ordering: false,
                            order: [],
                            ajax: {
                                url: "<?=base_url('api/dealer/part_stock_opname') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.id_gudang = form_.gudang.id_gudang;
                                },
                                type: "POST",
                            },
                            columns: [
                                { data: 'id_part' },
                                { data: 'nama_part'},
                                { data: 'id_gudang'},
                                { data: 'id_rak'},
                                { data: 'action'}
                            ],

                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>