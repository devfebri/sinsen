<!-- Modal -->
<div id="parts_transaksi_penjualan_inbound_form" class="modal" tabindex="-30" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Parts</h4>
        </div>
        <div class="modal-body">
            <table id="parts_transaksi_penjualan_inbound_form_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                    <th>ID Part</th>
                    <th>Nama Part</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                parts_transaksi_penjualan_inbound_form_datatable = $('#parts_transaksi_penjualan_inbound_form_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/dealer/parts_transaksi_penjualan_inbound_form') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_outbound_form = form_.inbound_form.id_outbound_form;
                        },
                    },
                    columns: [
                        { data: 'id_part' },
                        { data: 'nama_part' },
                        { data: 'action' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        // $('#parts_transaksi_penjualan_inbound_form').on('show.bs.modal', function (e) {
        //     $('#transaksi_penjualan_inbound_form').modal('toggle');
        // });

        // $('#parts_transaksi_penjualan_inbound_form').on('hide.bs.modal', function (e) {
        //     $('#transaksi_penjualan_inbound_form').modal('toggle');
        // });
    });
</script>