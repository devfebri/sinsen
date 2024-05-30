<!-- Modal -->
<div id="h3_md_lokasi_claim_main_dealer_ke_ahm" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Lokasi</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_lokasi_claim_main_dealer_ke_ahm_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Lokasi Rak</th>
                        <th>Deksripsi</th>
                        <th>Qty On Hand</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_lokasi_claim_main_dealer_ke_ahm_datatable = $('#h3_md_lokasi_claim_main_dealer_ke_ahm_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/lokasi_claim_main_dealer_ke_ahm') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            if(app.parts.length > 0){
                                d.id_part = app.parts[app.index_part].id_part;
                                d.id_part_int = app.parts[app.index_part].id_part_int;
                            }
                        }
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%' }, 
                        { data: 'kode_lokasi_rak' },
                        { data: 'deskripsi' },
                        { data: 'qty_onhand' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });

                h3_md_lokasi_claim_main_dealer_ke_ahm_datatable.on('draw.dt', function() {
                    var info = h3_md_lokasi_claim_main_dealer_ke_ahm_datatable.page.info();
                    h3_md_lokasi_claim_main_dealer_ke_ahm_datatable.column(0, {
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