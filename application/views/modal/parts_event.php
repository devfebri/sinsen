<div id="parts_event" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="parts_event_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID Part</th>
                            <th>Nama Part</th>
                            <th>Rak</th>
                            <th>Gudang</th>
                            <th>Stock</th>
                            <th>AVS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        parts_event_datatable = $('#parts_event_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?=base_url('api/dealer/parts_event') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.selected_parts = _.chain(form_.parts)
                                    .map(function(part){
                                        return _.pick(part, ['id_part', 'id_gudang', 'id_rak']);
                                    })
                                    .value();
                                    return d;
                                },
                                type: "POST",
                            },
                            columns: [
                                { data: 'index', width: '3%', orderable: false }, 
                                { data: 'id_part' }, 
                                { data: 'nama_part' },
                                { data: 'id_rak' },
                                { data: 'id_gudang' },
                                { data: 'stock' },
                                { data: 'stock_avs' },
                                { data: 'action', width: '3%', className: 'text-center', orderable: false }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>