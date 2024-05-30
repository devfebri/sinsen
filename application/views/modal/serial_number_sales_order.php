<div id='serial_number_sales_order'  class="modal fade modalcustomer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Serial Number</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="datatable_serial_number_sales_order" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Serial Number</th>
                            <th>Kode Lokasi Rak</th>
                            <th>Gudang Rak</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function(){
                        datatable_serial_number_sales_order = $('#datatable_serial_number_sales_order').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/serial_number_sales_order') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.nomor_so = form_.picking_slip.nomor_so;
                                    d.id_part_int = _.get(form_, 'parts_ev.['+ form_.indexPart +'].id_part_int');
                                    d.id_rak = _.get(form_, 'parts_ev.['+ form_.indexPart +'].id_rak');
                                    d.index = form_.indexPart;
                                },
                                type: "POST",
                            },
                            
                            columns: [{
                                data: 'serial_number'
                            },{
                                data: 'id_gudang_dealer'
                            },{
                                data: 'id_lokasi_rak_dealer'
                            },{
                                data: 'action'
                            },],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>