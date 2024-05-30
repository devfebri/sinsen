<!-- Modal -->
<div id="h3_dealer_kelompok_part_filter_laporan_stock_versi_kelompok" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Kelompok Part</h4>
            </div>
            <div class="modal-body">
                <table id="h3_dealer_kelompok_part_filter_laporan_stock_versi_kelompok_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kelompok Part</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_dealer_kelompok_part_filter_laporan_stock_versi_kelompok_datatable = $('#h3_dealer_kelompok_part_filter_laporan_stock_versi_kelompok_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/dealer/kelompok_part_filter_laporan_penjualan_per_part_number') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.filters = form_.filter_kelompok_produk;
                                d.start_date = form_.start_date;
                                d.end_date = form_.end_date;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'id_kelompok_part' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>