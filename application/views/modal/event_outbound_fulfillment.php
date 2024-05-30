<div id="event_outbound_fulfillment" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Event H23</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="event_outbound_fulfillment_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Event</th>
                            <th>Nama Event</th>
                            <th>Deskripsi</th>
                            <th>Lokasi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        event_outbound_fulfillment_datatable = $('#event_outbound_fulfillment_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?=base_url('api/dealer/event_outbound_fulfillment') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    return d;
                                },
                                type: "POST",
                            },
                            columns: [
                                { data: 'id_event' }, 
                                { data: 'nama' },
                                { data: 'deskripsi' },
                                { data: 'lokasi_event' },
                                { data: 'action', width: '3%', className: 'text-center', orderable: false, }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>