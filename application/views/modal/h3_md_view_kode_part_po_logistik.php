<!-- Modal -->
<div id="h3_md_view_kode_part_po_logistik" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_view_kode_part_po_logistik_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Tgl Request</th>
                            <th>No. Request</th>
                            <th>Qty NRFS</th>
                            <th>Qty Supply</th>
                            <th>Qty Book</th>
                            <th>Qty PO AHM</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_view_kode_part_po_logistik_datatable = $('#h3_md_view_kode_part_po_logistik_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/view_kode_part_po_logistik') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_part = _.get(app.parts, '[' + app.index_part + '].id_part');
                                d.id_po_logistik = app.po_logistik.id_po_logistik;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { 
                                data: 'tgl_request',
                                render: function(data){
                                    if(data != null){
                                        return data;
                                    }
                                    return '-';
                                }
                            },
                            { 
                                data: 'request_id',
                                render: function(data){
                                    if(data != null){
                                        return data;
                                    }
                                    return '-';
                                }
                            },
                            { data: 'qty_part' },
                            { data: 'qty_supply' },
                            { data: 'qty_book' },
                            { data: 'qty_po_ahm' },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>