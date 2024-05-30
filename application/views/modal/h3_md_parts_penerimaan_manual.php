<!-- Modal -->
<div id="h3_md_parts_penerimaan_manual" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_parts_penerimaan_manual_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Part Number</th>
                            <th>Part Deskripsi</th>
                            <th>Kelompok Part</th>
                            <th>HPP</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_parts_penerimaan_manual_datatable = $('#h3_md_parts_penerimaan_manual_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/parts_penerimaan_manual') ?>",
                            dataSrc: "data",
                            type: "POST"
                        },
                        columns: [
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'kelompok_part' },
                            { data: 'harga_formatted' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>