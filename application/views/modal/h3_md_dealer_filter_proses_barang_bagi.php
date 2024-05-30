<!-- Modal -->
<div id="h3_md_dealer_filter_proses_barang_bagi" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Customer</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_dealer_filter_proses_barang_bagi_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
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
                    h3_md_dealer_filter_proses_barang_bagi_datatable = $('#h3_md_dealer_filter_proses_barang_bagi_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/dealer_filter_proses_barang_bagi') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filter_dealers = form_.filter_dealers;
                            }
                        },
                        columns: [
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