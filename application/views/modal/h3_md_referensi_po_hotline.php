<!-- Modal -->
<div id="h3_md_referensi_po_hotline" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Referensi</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_referensi_po_hotline_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Referensi</th>
                            <th>Tipe Referensi</th>
                            <th>Nama Customer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_referensi_po_hotline_datatable = $('#h3_md_referensi_po_hotline_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/referensi_po_hotline') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                            data: function(d){
                                d.referensi_terpakai = app.referensi_terpakai;
                                d.jenis_po = app.purchase.jenis_po;
                            }
                        },
                        columns: [
                            { data: null, orderable: false, width: '3%'},
                            { data: 'referensi' },
                            { 
                                data: 'po_type',
                                render: function(data){
                                    if(data == 'URG') {
                                        return 'Logistik';
                                    }

                                    if (data == 'HLO') {
                                        return 'Hotline Dealer';
                                    }
                                }
                            },
                            { data: 'nama_dealer' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });

                    h3_md_referensi_po_hotline_datatable.on('draw.dt', function() {
                        var info = h3_md_referensi_po_hotline_datatable.page.info();
                        h3_md_referensi_po_hotline_datatable.column(0, {
                            search: 'applied',
                            order: 'applied',
                            page: 'applied'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = i + 1 + info.start + ".";
                        });
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>