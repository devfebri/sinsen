<!-- Modal -->
<div id="h3_md_ekspedisi_penerimaan_part_vendor" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Ekspedisi</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_ekspedisi_penerimaan_part_vendor_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nama Ekspedisi</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_ekspedisi_penerimaan_part_vendor_datatable = $('#h3_md_ekspedisi_penerimaan_part_vendor_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/ekspedisi_penerimaan_po_vendor') ?>",
                            dataSrc: "data",
                            type: "POST"
                        },
                        columns: [
                            { data: 'nama_ekspedisi' },
                            { data: 'alamat' },
                            { data: 'no_telp' },
                            { data: 'action', orderable: false, width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>