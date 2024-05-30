<!-- Modal -->
<div id="h3_md_collector_rekap_insentif_part" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Collector</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_collector_rekap_insentif_part_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Karyawan</th>
                            <th>Nama</th>
                            <th>NPK</th>
                            <th>Jabatan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_collector_rekap_insentif_part_datatable = $('#h3_md_collector_rekap_insentif_part_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/collector_rekap_insentif_part') ?>",
                            dataSrc: "data",
                            type: "POST",
                        },
                        columns: [
                            { data: 'index', width: '3%', orderable: false },
                            { data: 'id_karyawan' },
                            { data: 'nama_lengkap' },
                            { data: 'npk' },
                            { data: 'jabatan' },
                            { data: 'action', width: '3%', orderable: false, className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>