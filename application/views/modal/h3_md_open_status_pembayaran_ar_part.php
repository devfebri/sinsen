<input type="hidden" id='referensi_open_status_pembayaran'>
<!-- Modal -->
<div id="h3_md_open_status_pembayaran_ar_part" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Status Pembayaran</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_open_status_pembayaran_ar_part_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nomor BG</th>
                            <th>Nama Bank</th>
                            <th>Tanggal Jatuh Tempo BG</th>
                            <th>Jumlah BG</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_open_status_pembayaran_ar_part_datatable = $('#h3_md_open_status_pembayaran_ar_part_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        searching: false,
                        ordering: false,
                        ajax: {
                            url: "<?= base_url('api/md/h3/open_status_pembayaran_ar_part') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.referensi = $('#referensi_open_status_pembayaran').val();
                            }
                        },
                        columns: [
                            { data: 'nomor_bg' },
                            { data: 'nama_bank_bg' },
                            { data: 'tanggal_jatuh_tempo_bg' },
                            { data: 'jumlah_pembayaran' },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>