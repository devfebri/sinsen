<!-- Modal -->
<div id="sa_form_request_document" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">SA Form</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="sa_form_request_document_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>SA Form</th>
                            <th>No Buku Claim C2</th>
                            <th>No Claim C2</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        sa_form_request_document_datatable = $('#sa_form_request_document_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/sa_form_request_document') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    return d;
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' }, 
                                { data: 'id_sa_form' }, 
                                { 
                                    data: 'no_buku_claim_c2',
                                    render: function(data){
                                        if(data == null || data == ''){
                                            return '-';
                                        }
                                        return data;
                                    }
                                }, 
                                { 
                                    data: 'no_claim_c2',
                                    render: function(data){
                                        if(data == null || data == ''){
                                            return '-';
                                        }
                                        return data;
                                    }
                                }, 
                                { data: 'action', orderable:false, className: 'text-center', width: '3%' }
                            ],
                        });   
                    });
                </script>
            </div>
        </div>
    </div>
</div>