<!-- Modal -->
<div id="h3_md_kelompok_vendor_part" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Kelompok Vendor</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_kelompok_vendor_part_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Kelompok Vendor</th>
                            <th>Nama Kelompok Vendor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_kelompok_vendor_part_datatable = $('#h3_md_kelompok_vendor_part_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/h3_md_kelompok_vendor_part') ?>",
                            dataSrc: "data",
                            type: "POST"
                        },
                        columns: [
                            { data: 'id_kelompok_vendor' },
                            { data: 'kelompok_vendor' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>