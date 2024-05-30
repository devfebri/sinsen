<!-- Modal -->
<div id="h3_md_parts_do_sales_order" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_parts_do_sales_order_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Kelompok Part</th>
                            <th>HET</th>
                            <th>Qty Onhand</th>
                            <th>Qty In Transit</th>
                            <th>Qty Booking</th>
                            <th>Qty AVS</th>
                            <th>Status</th>
                            <th>Tipe Motor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_parts_do_sales_order_datatable = $('#h3_md_parts_do_sales_order_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/parts_do_sales_order') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_sales_order = app.do_sales_order.id_sales_order;
                                d.id_part = _.map(app.parts, function(p){
                                    return p.id_part;
                                });
                            }
                        },
                        columns: [
                            { data: null, orderable: false, width: '3%' }, 
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'kelompok_part' },
                            { 
                                data: 'harga_jual', 
                                className: 'text-right', 
                                width: '70px',
                                render: function(data){
                                  return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                                }
                            },
                            { data: 'qty_onhand', orderable: false, width: '30px' },
                            { data: 'qty_intransit', orderable: false, width: '30px' },
                            { data: 'qty_booking', orderable: false, width: '30px' },
                            { data: 'qty_avs', orderable: false, width: '30px' },
                            { data: 'status' },
                            { data: 'view_tipe_motor', orderable: false, width: '3%', className: 'text-center' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });

                    h3_md_parts_do_sales_order_datatable.on('draw.dt', function() {
                        info = h3_md_parts_do_sales_order_datatable.page.info();
                        h3_md_parts_do_sales_order_datatable.column(0, {
                            search: 'applied',
                            order: 'applied',
                            page: 'applied'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = i + 1 + info.start + ".";
                        });
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>