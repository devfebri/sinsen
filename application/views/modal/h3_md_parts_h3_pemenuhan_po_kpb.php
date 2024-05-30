<!-- Modal -->
<div id="h3_md_parts_h3_pemenuhan_po_kpb" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_parts_h3_pemenuhan_po_kpb_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Qty AVS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_parts_h3_pemenuhan_po_kpb_datatable = $('#h3_md_parts_h3_pemenuhan_po_kpb_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/part_h3_po_pemenuhan_kpb') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_detail = $('#id-detail-for-modal').val();
                                d.tipe_produksi = $('#tipe-produksi-for-modal').val();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' }, 
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { 
                                data: 'qty_avs', orderable: false, width: '30px',
                                render: function(data){
                                    return accounting.format(data, 0, '.', ',');
                                },
                                width: '30%'
                            },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>