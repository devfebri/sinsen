<div id='rak_parts_sales_order'  class="modal fade modalcustomer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Gudang dan Rak</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable_rak_parts_sales_order" style="width: 100%">
                    <thead>
                        <tr>
                            <th>ID Rak</th>
                            <th>Rak Deskripsi</th>
                            <th>ID Gudang</th>
                            <th>Deskripsi Gudang</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function(){
                        datatable_rak_parts_sales_order = $('#datatable_rak_parts_sales_order').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/rak') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.id_part = _.get(form_, 'parts.['+ form_.indexPart +'].id_part');
                                    d.index = form_.indexPart;
                                },
                                type: "POST"
                            },
                            columns: [{
                                data: 'id_rak'
                            }, {
                                data: 'deskripsi_rak'
                            }, {
                                data: 'id_gudang'
                            }, {
                                data: 'deskripsi_gudang'
                            },{
                                data: 'action'
                            },],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>