<!-- Modal -->
<div id="h3_md_voucher_pengeluaran_entry_pengeluaran_bank" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Voucher Pengeluaran</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_voucher_pengeluaran_entry_pengeluaran_bank_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Voucher Pengeluaran</th>
                            <th>Tanggal</th>
                            <th>Tipe Penerima</th>
                            <th>Via Bayar</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_voucher_pengeluaran_entry_pengeluaran_bank_datatable = $('#h3_md_voucher_pengeluaran_entry_pengeluaran_bank_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/voucher_pengeluaran_entry_pengeluaran_bank') ?>",
                            dataSrc: "data",
                            type: "POST"
                        },
                        columns: [
                            { data: 'index', width: '3%', orderable: false },
                            { data: 'id_voucher_pengeluaran' },
                            { 
                                data: 'tanggal_transaksi',
                                render: function(data){
                                    return moment(data).format('DD/MM/YYYY');
                                }
                            },
                            { data: 'tipe_penerima' },
                            { data: 'via_bayar' },
                            { 
                                data: 'total_amount',
                                render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', ',', '.');
                                }
                            },
                            { data: 'action', width: '3%', orderable: false, className: 'text-center' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>