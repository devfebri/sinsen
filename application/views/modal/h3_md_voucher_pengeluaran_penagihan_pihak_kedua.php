<!-- Modal -->
<div id="h3_md_voucher_pengeluaran_penagihan_pihak_kedua" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 80%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Voucher Pengeluaran</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_voucher_pengeluaran_penagihan_pihak_kedua_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Voucher Pengeluaran</th>
                            <th>Tanggal Transaksi</th>
                            <th>Nama Vendor</th>
                            <th>Nominal Pembayaran</th>
                            <th>No. Giro</th>
                            <th>Nominal Giro</th>
                            <th>Divisi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_voucher_pengeluaran_penagihan_pihak_kedua_datatable = $('#h3_md_voucher_pengeluaran_penagihan_pihak_kedua_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/voucher_pengeluaran_penagihan_pihak_kedua') ?>",
                            dataSrc: "data",
                            type: "POST"
                        },
                        columns: [
                            { data: 'index', width: '3%', orderable: false },
                            { data: 'id_voucher_pengeluaran' },
                            { 
                                data: 'tanggal_transaksi' ,
                                render: function(data){
                                    return moment(data).format('DD/MM/YYYY');
                                }
                            },
                            { data: 'nama_penerima_dibayarkan_kepada' },
                            { 
                                data: 'total_amount',
                                render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                                },
                                className: 'text-right'
                            },
                            { 
                                data: 'no_giro',
                                render: function(data){
                                    if(data != null){
                                        return data;
                                    }
                                    return '-';
                                }
                            },
                            { 
                                data: 'nominal_giro',
                                render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                                },
                                className: 'text-right'
                            },
                            { data: 'divisi' },
                            { data: 'action', width: '3%', orderable: false, className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>