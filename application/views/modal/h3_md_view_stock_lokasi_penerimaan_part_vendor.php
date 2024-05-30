<input type="hidden" id='id_lokasi_rak_view_stock'>
<!-- Modal -->
<div id="h3_md_view_stock_lokasi_penerimaan_part_vendor" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Stock</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_view_stock_lokasi_penerimaan_part_vendor_datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Qty On Hand</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        h3_md_view_stock_lokasi_penerimaan_part_vendor_datatable = $('#h3_md_view_stock_lokasi_penerimaan_part_vendor_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: '<?= base_url('api/md/h3/view_stock_lokasi_penerimaan_barang') ?>',
                                dataSrc: 'data',
                                type: 'POST',
                                data: function(d){
                                    d.id = $('#id_lokasi_rak_view_stock').val();
                                }
                            },
                            columns: [
                                { data: null, orderable: false, width: '3%'},
                                { data: 'id_part' },
                                { data: 'nama_part' },
                                { 
                                    data: 'qty',
                                    render: function(data){
                                        return accounting.formatMoney(data, "", 0, ".", ",");
                                    }
                                },
                            ],
                        });

                        h3_md_view_stock_lokasi_penerimaan_part_vendor_datatable.on('draw.dt', function() {
                            var info = h3_md_view_stock_lokasi_penerimaan_part_vendor_datatable.page.info();
                            h3_md_view_stock_lokasi_penerimaan_part_vendor_datatable.column(0, {
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
<script>
    $(document).ready(function(){
        $('#h3_md_view_stock_lokasi_penerimaan_part_vendor').on('hidden.bs.modal', function () {
            $('#h3_md_lokasi_penerimaan_part_vendor').modal('show');   
        });
    });
</script>
