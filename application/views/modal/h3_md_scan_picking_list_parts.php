<!-- Modal -->
<div id="h3_md_scan_picking_list_parts" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" width='80%'>
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Parts</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_scan_picking_list_parts_datatable" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Part Number</th>
                        <th>Part Deskripsi</th>
                        <th>Serial Number</th>
                        <th>Tipe Kendaraan</th>
                        <th>Qty DO</th>
                        <th>Qty Picking</th>
                        <th>Qty Sudah Scan</th>
                        <th>Qty Belum Scan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_scan_picking_list_parts_datatable = $('#h3_md_scan_picking_list_parts_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: '<?= base_url('api/md/h3/scan_picking_list_parts') ?>',
                        dataSrc: 'data',
                        type: 'POST',
                        data: function(data){
                            data.id_picking_list = app.picking_list.id_picking_list;
                            data.kategori_po = app.picking_list.kategori_po;
                            data.is_ev = app.picking_list.is_ev;
                        }
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'id_part' },
                        { data: 'nama_part', width: '200px' },
                        { data: 'serial_number', width: '200px' },
                        { 
                            data: 'id_tipe_kendaraan' ,
                            render: function(data){
                                if(data != null){
                                    return data;
                                }

                                return '-';
                            }
                        },
                        { data: 'qty_do' },
                        { data: 'qty_picking' },
                        { data: 'qty_sudah_scan' },
                        { data: 'qty_belum_scan' },
                        { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                    ],
                });

                h3_md_scan_picking_list_parts_datatable.on('draw.dt', function() {
                    var info = h3_md_scan_picking_list_parts_datatable.page.info();
                    h3_md_scan_picking_list_parts_datatable.column(0, {
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