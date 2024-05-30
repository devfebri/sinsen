<!-- Modal -->
<div id="h3_md_debt_collector_filter_penerimaan_pembayaran_index" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Debt Collector</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_debt_collector_filter_penerimaan_pembayaran_index_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>NPK</th>
                        <th>Nama</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_debt_collector_filter_penerimaan_pembayaran_index_datatable = $('#h3_md_debt_collector_filter_penerimaan_pembayaran_index_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/debt_collector_filter_penerimaan_pembayaran_index') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_debt_collector_filter = $('#id_debt_collector_filter').val();
                        }
                    },
                    columns: [
                        { data: 'npk' },
                        { data: 'nama_lengkap' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>