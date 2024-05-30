<!-- Modal -->
<input type="hidden" id='id_penerimaan_barang_item_for_reason_ahm'>
<div id="h3_md_reason_ahm_penerimaan_barang" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Reason Claim to AHM</h4>
            </div>
            <div class="modal-body">
                <table id='h3_md_reason_ahm_penerimaan_barang_datatable' class="table table-condensed">
                    <thead>
                        <tr>
                            <th width='5%'>No.</th>
                            <th width='5%'>Kode Claim</th>
                            <th>Nama Claim</th>
                            <th class='text-center' width='5%'></th>
                            <th width='10%'>Qty</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    // $(document).ready(function(){
                    //     h3_md_reason_ahm_penerimaan_barang_datatable = $('#h3_md_reason_ahm_penerimaan_barang_datatable').DataTable({
                    //         processing: true,
                    //         serverSide: true,
                    //         searching: false,
                    //         order: [],
                    //         ordering: false,
                    //         ajax: {
                    //             url: "<?= base_url('api/md/h3/reason_ahm_kekurangan_part') ?>",
                    //             dataSrc: 'data',
                    //             type: "POST",
                    //             data: function(d){
                    //                 d.id_penerimaan_barang_item_for_reason_ahm = $('#id_penerimaan_barang_item_for_reason_ahm').val();
                    //             }
                    //         },
                    //         columns: [
                    //             { data: null, orderable: false, width: '3%' }, 
                    //             { data: 'kode_claim' }, 
                    //             { data: 'nama_claim' }, 
                    //             { 
                    //                 data: 'checked',
                    //                 className: 'text-center',
                    //                 render: function(data){
                    //                     if(data == 1){
                    //                         return '<input type="checkbox" checked disabled>';
                    //                     }else{
                    //                         return '<input type="checkbox" disabled>';
                    //                     }
                    //                 }
                    //             }, 
                    //             { 
                    //                 data: 'qty',
                    //             }, 
                    //             { data: 'keterangan' }, 
                    //         ],
                    //     });
                    //     h3_md_reason_ahm_penerimaan_barang_datatable.on('draw.dt', function() {
                    //         var info = h3_md_reason_ahm_penerimaan_barang_datatable.page.info();
                    //         h3_md_reason_ahm_penerimaan_barang_datatable.column(0, {
                    //             search: 'applied',
                    //             order: 'applied',
                    //             page: 'applied'
                    //         }).nodes().each(function(cell, i) {
                    //             cell.innerHTML = i + 1 + info.start + ".";
                    //         });
                    //     });
                    // });

                    var h3_md_reason_ahm_penerimaan_barang_datatable;
                        function drawing_reason_ahm_penerimaan_barang(){
                        h3_md_reason_ahm_penerimaan_barang_datatable = $('#h3_md_reason_ahm_penerimaan_barang_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            searching: false,
                            order: [],
                            ordering: false,
                            bDestroy: true, 
                            ajax: {
                                url: "<?= base_url('api/md/h3/reason_ahm_kekurangan_part') ?>",
                                dataSrc: 'data',
                                type: "POST",
                                data: function(d){
                                    d.id_penerimaan_barang_item_for_reason_ahm = $('#id_penerimaan_barang_item_for_reason_ahm').val();
                                }
                            },
                            columns: [
                                { data: null, orderable: false, width: '3%' }, 
                                { data: 'kode_claim' }, 
                                { data: 'nama_claim' }, 
                                { 
                                    data: 'checked',
                                    className: 'text-center',
                                    render: function(data){
                                        if(data == 1){
                                            return '<input type="checkbox" checked disabled>';
                                        }else{
                                            return '<input type="checkbox" disabled>';
                                        }
                                    }
                                }, 
                                { 
                                    data: 'qty',
                                }, 
                                { data: 'keterangan' }, 
                            ],
                        });
                        h3_md_reason_ahm_penerimaan_barang_datatable.on('draw.dt', function() {
                            var info = h3_md_reason_ahm_penerimaan_barang_datatable.page.info();
                            h3_md_reason_ahm_penerimaan_barang_datatable.column(0, {
                                search: 'applied',
                                order: 'applied',
                                page: 'applied'
                            }).nodes().each(function(cell, i) {
                                cell.innerHTML = i + 1 + info.start + ".";
                            });
                        });
                    };

                    function open_view_reason_ahm(id_penerimaan_barang_item){
                        $('#id_penerimaan_barang_item_for_reason_ahm').val(id_penerimaan_barang_item);
                        // h3_md_reason_ahm_penerimaan_barang_datatable.draw();
                        drawing_reason_ahm_penerimaan_barang();
                        $('#h3_md_pop_up_kekurangan_part').modal('hide');
                        $('#h3_md_reason_ahm_penerimaan_barang').modal('show');
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#h3_md_reason_ahm_penerimaan_barang').on('hidden.bs.modal', function (e) {
            $('#h3_md_pop_up_kekurangan_part').modal('show');
        });
    });
</script>