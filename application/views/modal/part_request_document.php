<!-- Modal -->
<div id="part_request_document" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="part_request_document_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Nama Part Bahasa</th>
                            <th>Status</th>
                            <th>Kelompok Part</th>
                            <th>HET</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        part_request_document_datatable = $('#part_request_document_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/parts_request_document') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.claim = form_.penomoran_ulang;
                                    d.order_to = form_.request_document.order_to;
                                    if(form_.claim_c1_c2_terpilih){
                                        d.tipe_claim = 'renumbering_claim';
                                    }else{
                                        d.tipe_claim = 'renumbering_non_claim';
                                    }

                                    d.id_part = _.chain(form_.parts)
                                    .map(function(part){
                                        return part.id_part;
                                    })
                                    .value();
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' }, 
                                { data: 'id_part' }, 
                                { data: 'nama_part' }, 
                                { data: 'nama_part_bahasa' }, 
                                { data: 'status' }, 
                                { data: 'kelompok_part' },
                                { 
                                    data: 'harga_saat_dibeli', 
                                    name: 'mp.harga_dealer_user',
                                    render: function(data){
                                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                                    },
                                    className: 'text-right'
                                },
                                { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>