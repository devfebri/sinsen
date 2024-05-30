<!-- Modal -->
<div id="h3_md_lokasi_penerimaan_part_vendor" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Lokasi</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_lokasi_penerimaan_part_vendor_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Lokasi Rak</th>
                            <th>Deskripsi Lokasi Rak</th>
                            <th>Kapasitas</th>
                            <th>Kapasitas Terpakai</th>
                            <th>Kapasitas Tersedia</th>
                            <th>Nama Gudang</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_lokasi_penerimaan_part_vendor_datatable = $('#h3_md_lokasi_penerimaan_part_vendor_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/lokasi_penerimaan_po_vendor') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_part = _.get(app.parts, '['+ app.index_part +'].id_part');
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'kode_lokasi_rak' },
                            { data: 'deskripsi' },
                            { 
                                data: 'kapasitas',
                                render: function(data){
                                    return accounting.formatMoney(data, "", 0, ".", ",");
                                }
                            },
                            { 
                                data: 'kapasitas_terpakai',
                                render: function(data){
                                    return accounting.formatMoney(data, "", 0, ".", ",");
                                }
                            },
                            { 
                                data: 'kapasitas_tersedia',
                                render: function(data){
                                    return accounting.formatMoney(data, "", 0, ".", ",");
                                }
                            },
                            { data: 'nama_gudang' },
                            { data: 'view_stock', orderable: false, width: '3%', className: 'text-center' },
                            { data: 'action', orderable: false, width: '3%' }
                        ],
                    });
                });

                function open_view_stock(id) {
                    $('#id_lokasi_rak_view_stock').val(id);
                    h3_md_view_stock_lokasi_penerimaan_part_vendor_datatable.draw();
                    $('#h3_md_lokasi_penerimaan_part_vendor').modal('hide');   
                    $('#h3_md_view_stock_lokasi_penerimaan_part_vendor').modal('show');
                }
                </script>
            </div>
        </div>
    </div>
</div>