<!-- Modal -->
<div id="h3_md_po_vendor_penerimaan_manual" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Vendor</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_po_vendor_penerimaan_manual_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No. PO Vendor</th>
                            <th>Tanggal</th>
                            <th>Nama Vendor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_po_vendor_penerimaan_manual_datatable = $('#h3_md_po_vendor_penerimaan_manual_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/h3_md_po_vendor_penerimaan_manual') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_vendor = app.penerimaan_manual.id_vendor;
                            }
                        },
                        columns: [
                            { data: 'id_po_vendor' },
                            { data: 'tanggal' },
                            { data: 'vendor_name' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>