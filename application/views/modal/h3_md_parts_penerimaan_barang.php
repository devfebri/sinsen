<!-- Modal -->
<div id="h3_md_parts_penerimaan_barang" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_parts_penerimaan_barang_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Part Number</th>
                            <th>Part Deskripsi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_parts_penerimaan_barang_datatable = $('#h3_md_parts_penerimaan_barang_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: '<?= base_url('api/md/h3/parts_penerimaan_barang') ?>',
                            dataSrc: 'data',
                            type: 'POST',
                            data: function(d){
                                d.list_surat_jalan_ahm = form_.surat_jalan_ahm;
                                d.list_packing_sheet_number_int = _.map(form_.list_packing_sheet_number, function(data){
                                    return data.packing_sheet_number_int;
                                });
                                d.list_nomor_karton = _.map(form_.list_nomor_karton, function(data){
                                    return data.nomor_karton;
                                });
                                d.list_part_number = form_.list_part_number;
                            }
                        },
                        columns: [
                            { data: null, orderable: false, width: '3%'},
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>