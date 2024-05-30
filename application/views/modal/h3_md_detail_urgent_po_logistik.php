<!-- Modal -->
<div id="h3_md_detail_urgent_po_logistik" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 80%'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Detail NRFS</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_detail_urgent_po_logistik_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Checker</th>
                            <th>Tanggal Checker</th>
                            <th>Tipe Kendaraan</th>
                            <th>Deskripsi Kendaraan</th>
                            <th>Warna</th>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
                            <th>Kuantitas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_detail_urgent_po_logistik_datatable = $('#h3_md_detail_urgent_po_logistik_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/detail_urgent_po_logistik') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                            data: function(d){
                                d.id_part = app.parts[app.index_part].id_part;
                                d.po_id = app.purchase.po_id;
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%'},
                            { data: 'referensi' },
                            { 
                                data: 'tgl_checker',
                                render: function(data){
                                    return moment(data).format('DD/MM/YYYY');
                                }
                            },
                            { data: 'tipe_motor' },
                            { data: 'deskripsi_unit' },
                            { data: 'deskripsi_warna' },
                            { data: 'no_mesin' },
                            { data: 'no_rangka' },
                            { 
                                data: 'kuantitas', 
                                render: function(data){
                                    return accounting.formatNumber(data, 0, ',', '.');
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