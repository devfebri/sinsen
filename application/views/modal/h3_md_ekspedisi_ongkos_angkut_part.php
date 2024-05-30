<!-- Modal -->
<div id="h3_md_ekspedisi_ongkos_angkut_part" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Ekspedisi</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_ekspedisi_ongkos_angkut_part_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Pemilik</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_ekspedisi_ongkos_angkut_part_datatable = $('#h3_md_ekspedisi_ongkos_angkut_part_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/ekspedisi_ongkos_angkut_part') ?>',
                        dataSrc: 'data',
                        type: 'POST'
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'nama_ekspedisi' },
                        { data: 'nama_pemilik' },
                        { data: 'no_telp' },
                        { data: 'alamat' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });

                h3_md_ekspedisi_ongkos_angkut_part_datatable.on('draw.dt', function() {
                    var info = h3_md_ekspedisi_ongkos_angkut_part_datatable.page.info();
                    h3_md_ekspedisi_ongkos_angkut_part_datatable.column(0, {
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