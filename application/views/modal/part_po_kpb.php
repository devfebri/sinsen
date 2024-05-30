<!-- Modal -->
<div id="h2_md_part_po_kpb" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h2_md_part_po_kpb_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        h2_md_part_po_kpb_datatable = $('#h2_md_part_po_kpb_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/part_po_kpb') ?>",
                                dataSrc: "data",
                                type: "POST",
                                data: function(d) {
                                    // d.tipe_produksi = _.get(app.parts, '[' + app.index_part + '].tipe_produksi');
                                }
                            },
                            columns: [{
                                    data: 'index',
                                    orderable: false,
                                    width: '3%'
                                },
                                {
                                    data: 'id_part'
                                },
                                {
                                    data: 'nama_part'
                                },
                                {
                                    data: 'action',
                                    orderable: false,
                                    width: '3%',
                                    className: 'text-center'
                                }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>