<!-- Modal -->
<div id="h3_md_coa_voucher_pengeluaran" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">COA</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_coa_voucher_pengeluaran_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Kode COA</th>
                        <th>Nama COA</th>
                        <th>Tipe Transaksi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_coa_voucher_pengeluaran_datatable = $('#h3_md_coa_voucher_pengeluaran_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    searchDelay: 1000,
                    ajax: {
                        url: "<?= base_url('api/md/h3/coa_voucher_pengeluaran') ?>",
                        dataSrc: "data",
                        type: "POST",
                    },
                    columns: [
                        { data: 'kode_coa' },
                        { data: 'coa' },
                        { data: 'tipe_transaksi' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>