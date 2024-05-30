<!-- Modal -->
<input type="hidden" id='id_penerimaan_barang_item_for_reason_ekspedisi'>
<div id="h3_md_reason_ekspedisi_penerimaan_barang" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Reason Ekspedisi</h4>
            </div>
            <div class="modal-body">
                <table id='h3_md_reason_ekspedisi_penerimaan_barang_datatable' class="table table-condensed">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th width='5%'>Kode Claim</th>
                            <th>Nama Claim</th>
                            <th class='text-center' width='5%'>Action</th>
                            <th width='10%'>Qty</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    // $(document).ready(function(){
                    //     h3_md_reason_ekspedisi_penerimaan_barang_datatable = $('#h3_md_reason_ekspedisi_penerimaan_barang_datatable').DataTable({
                    //         processing: true,
                    //         serverSide: true,
                    //         searching: false,
                    //         order: [],
                    //         ordering: false,
                    //         ajax: {
                    //             url: "<?= base_url('api/md/h3/reason_ekspedisi_kekurangan_part') ?>",
                    //             dataSrc: 'data',
                    //             type: "POST",
                    //             data: function(d){
                    //                 d.id_penerimaan_barang_item_for_reason_ekspedisi = $('#id_penerimaan_barang_item_for_reason_ekspedisi').val();
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
                    //     h3_md_reason_ekspedisi_penerimaan_barang_datatable.on('draw.dt', function() {
                    //         var info = h3_md_reason_ekspedisi_penerimaan_barang_datatable.page.info();
                    //         h3_md_reason_ekspedisi_penerimaan_barang_datatable.column(0, {
                    //             search: 'applied',
                    //             order: 'applied',
                    //             page: 'applied'
                    //         }).nodes().each(function(cell, i) {
                    //             cell.innerHTML = i + 1 + info.start + ".";
                    //         });
                    //     });
                    // });

                    var h3_md_reason_ekspedisi_penerimaan_barang_datatable;
                        function drawing_reason_ekpedisi(){
                        h3_md_reason_ekspedisi_penerimaan_barang_datatable = $('#h3_md_reason_ekspedisi_penerimaan_barang_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            searching: false,
                            order: [],
                            ordering: false,
                            bDestroy: true, 
                            ajax: {
                                url: "<?= base_url('api/md/h3/reason_ekspedisi_kekurangan_part') ?>",
                                dataSrc: 'data',
                                type: "POST",
                                data: function(d){
                                    d.id_penerimaan_barang_item_for_reason_ekspedisi = $('#id_penerimaan_barang_item_for_reason_ekspedisi').val();
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
                        h3_md_reason_ekspedisi_penerimaan_barang_datatable.on('draw.dt', function() {
                            var info = h3_md_reason_ekspedisi_penerimaan_barang_datatable.page.info();
                            h3_md_reason_ekspedisi_penerimaan_barang_datatable.column(0, {
                                search: 'applied',
                                order: 'applied',
                                page: 'applied'
                            }).nodes().each(function(cell, i) {
                                cell.innerHTML = i + 1 + info.start + ".";
                            });
                        });
                    };

                    function open_view_reason_ekspedisi(id_penerimaan_barang_item){
                        $('#id_penerimaan_barang_item_for_reason_ekspedisi').val(id_penerimaan_barang_item);
                        // h3_md_reason_ekspedisi_penerimaan_barang_datatable.draw();
                        drawing_reason_ekpedisi();
                        $('#h3_md_pop_up_kekurangan_part').modal('hide');
                        $('#h3_md_reason_ekspedisi_penerimaan_barang').modal('show');
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#h3_md_reason_ekspedisi_penerimaan_barang').on('hidden.bs.modal', function (e) {
            for (reason of form_.reasons_ekspedisi) {
                index_reason = _.findIndex(form_.parts[form_.index_part].reasons, function(data){
                    return data.kode_claim == reason.kode_claim;
                });
                form_.parts[form_.index_part].reasons[index_reason] = reason;
            }
            form_.reasons = [];
            $('#h3_md_pop_up_kekurangan_part').modal('show');
        });
    });
</script>
