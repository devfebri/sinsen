<!-- Modal -->
<div id="h3_md_rekening_tujuan_transfer_penerimaan_pembayaran" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Rekening Tujuan BG</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_rekening_tujuan_transfer_penerimaan_pembayaran_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Nama Bank</th>
                        <th>Nomor Rekening</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_rekening_tujuan_transfer_penerimaan_pembayaran_datatable = $('#h3_md_rekening_tujuan_transfer_penerimaan_pembayaran_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/rekening_tujuan_transfer_penerimaan_pembayaran') ?>",
                        dataSrc: "data",
                        type: "POST",
                    },
                    columns: [
                        { data: 'bank' },
                        { data: 'no_rekening' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>