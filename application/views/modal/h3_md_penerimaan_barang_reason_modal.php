<!-- Modal -->
<div id="h3_md_penerimaan_barang_reason_modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Reason</h4>
            </div>
            <div class="modal-body">
                            <table id="reason_penerimaan_barang_datatable" class="table table-condensed">
                                <tr>
                                    <td>Kode</td>
                                    <td>Tipe Claim</td>
                                    <td>Nama Claim</td>
                                    <td>Qty</td>
                                    <td>Keterangan</td>
                                </tr>
                                <tr>
                                
                                </tr>
                            </table>
              <script>
              $(document).ready(function() {
                reason_penerimaan_barang_datatable = $('#reason_penerimaan_barang_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching:false,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/reason_penerimaan_barang') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                        d.no_karton = $('#no_karton').val();
                        d.no_penerimaan_barang = $('#no_penerimaan_barang').val();
                        d.id_part = $('#id_part').val();
                        }
                    },
                    columns: [
                        { data: 'kode_claim'}, 
                        { data: 'tipe_claim'},
                        { data: 'nama_claim'}, 
                        { data: 'qty'},
                        { data: 'keterangan'}
                        
                    ],
                });
                
                // alert(no_karton + "test");
              });
              </script>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
