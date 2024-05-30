<!-- Modal -->
<div id="h3_md_vendor_penagihan_pihak_kedua" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Vendor</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_vendor_penagihan_pihak_kedua_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Vendor</th>
                            <th>Nama Vendor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_vendor_penagihan_pihak_kedua_datatable = $('#h3_md_vendor_penagihan_pihak_kedua_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/vendor_penagihan_pihak_kedua') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_vendor = _.chain(app.penagihan_tujuan)
                                .map(function(row){
                                    return row.id_vendor;
                                })
                                .value();
                            }
                        },
                        columns: [
                            { data: 'index', width: '3%', orderable: false },
                            { data: 'id_vendor' },
                            { data: 'vendor_name' },
                            { data: 'action', width: '3%', orderable: false, className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>