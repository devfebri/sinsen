<!-- Modal -->
<div id="h3_md_open_eta_history" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">History ETA</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_open_eta_history_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ETA</th>
                            <th>Source</th>
                            <th>Dibuat pada tanggal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_open_eta_history_datatable = $('#h3_md_open_eta_history_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        orderable: false,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/open_eta_history') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                            data: function(d){
                                d.id_part = _.get(app.parts, '['+ app.index_part +'].id_part');
                                d.id_purchase_order = app.purchase.id_purchase_order;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%'},
                            { data: 'eta' },
                            { 
                                data: 'source',
                                render: function(data){
                                    if(data == 'setting_master'){
                                        return 'Settingan Master';
                                    }
                                    if(data == 'upload_revisi'){
                                        return 'Upload Revisi'
                                    }
                                    return data;
                                }
                            },
                            { data: 'created_at' },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>