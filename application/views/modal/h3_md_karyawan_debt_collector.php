<!-- Modal -->
<div id="h3_md_karyawan_debt_collector" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Domisili</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_karyawan_debt_collector_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>No Telepon</th>
                        <th>Tanggal Masuk Kerja</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_karyawan_debt_collector_datatable = $('#h3_md_karyawan_debt_collector_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/karyawan_debt_collector') ?>",
                        dataSrc: "data",
                        type: "POST",
                    },
                    columns: [
                        { data: 'npk' },
                        { data: 'nama_lengkap' },
                        { data: 'no_telp' },
                        { data: 'tgl_masuk' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>