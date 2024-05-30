<!-- Modal -->
<div id="wo_request_document" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Work Order</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="wo_request_document_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID Work Order</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        wo_request_document_datatable = $('#wo_request_document_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/wo_request_document') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.id_sa_form  = form_.request_document.id_sa_form;
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' }, 
                                { data: 'id_work_order' }, 
                                { data: 'action', orderable:false, className: 'text-center', width: '3%' }
                            ],
                        });   
                    });
                </script>
            </div>
        </div>
    </div>
</div>