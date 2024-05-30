<!-- Modal -->
<div id="h3_md_view_qty_hotline_online_stock_dealer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid bg-primary" style='padding: 5px 0px;'>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <span class='text-bold'>Qty Booking</span>
                        </div>
                    </div>
                </div>
                <table id="h3_md_view_qty_hotline_online_stock_dealer_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No PO HO</th>
                            <th>Tgl PO Hotline</th>
                            <th>Qty PO Hotline</th>
                            <th>Nama Konsumen</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_view_qty_hotline_online_stock_dealer_datatable = $('#h3_md_view_qty_hotline_online_stock_dealer_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        searching: false,
                        lengthChange: false,
                        info: false,
                        paging: false,
                        ajax: {
                            url: "<?= base_url('api/md/h3/qty_hotline_online_stock_dealer') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_customer_filter = $('#id_customer_filter').val();
                                d.id_part_for_view_qty_hotline = $('#id_part_for_view_qty_hotline').val();
                            }
                        },
                        columns: [
                            { data: 'po_id' },
                            { data: 'tanggal_order' },
                            { data: 'kuantitas' },
                            { data: 'nama_customer' },
                            { 
                                data: 'penyerahan_customer',
                                render: function(data){
                                    if(data == 1){
                                        return 'Telah diterima Konsumen';
                                    }
                                    return 'Belum diterima Konsumen';
                                }
                            },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>