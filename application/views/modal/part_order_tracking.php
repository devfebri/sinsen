<!-- Modal -->
<div id="part_order_tracking_modal" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="part_order_tracking_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>ID Part</th>
                            <th>Nama Part</th>
                            <th>Kelompok Vendor</th>
                            <th>Harga</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        part_order_tracking_datatable = $('#part_order_tracking_datatable').DataTable({ssing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/part_order_tracking') ?>",
                                dataSrc: "data",
                                data: function(d) {

                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'id_part' },
                                { data: 'nama_part' },
                                { data: 'kelompok_vendor' },
                                { data: 'harga' },
                                {  data: 'action', width: '3%', className: 'text-center', orderable: false }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>