<!-- Modal -->
<div id="h3_md_dealer_filter_monitoring_supply" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style='width: 70%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Customer</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_dealer_filter_monitoring_supply_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Kode Dealer</th>
                            <th>Nama Dealer</th>
                            <th>Kab/Kota</th>
                            <th>Alamat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_dealer_filter_monitoring_supply_datatable = $('#h3_md_dealer_filter_monitoring_supply_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/dealer_filter_monitoring_supply') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_customer_filter = $('#id_customer_filter').val();
                            }
                        },
                        columns: [
                            { data: 'kode_dealer_md' },
                            { data: 'nama_dealer' },
                            { data: 'kabupaten' },
                            { data: 'alamat' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>