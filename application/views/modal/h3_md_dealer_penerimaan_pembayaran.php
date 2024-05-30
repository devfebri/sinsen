<!-- Modal -->
<div id="h3_md_dealer_penerimaan_pembayaran" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Customer</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_dealer_penerimaan_pembayaran_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Customer</th>
                        <th>Nama Customer</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_dealer_penerimaan_pembayaran_datatable = $('#h3_md_dealer_penerimaan_pembayaran_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/dealer_penerimaan_pembayaran') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_group_dealer = form_.penerimaan_pembayaran.id_group_dealer;
                            d.id_debt_collector = form_.penerimaan_pembayaran.id_debt_collector;
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%' },
                        { data: 'kode_dealer_md' },
                        { data: 'nama_dealer' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>