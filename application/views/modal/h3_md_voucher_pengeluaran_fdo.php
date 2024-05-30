<input type="hidden" id='invoice_number_voucher_pengeluaran_pop_up'>
<!-- Modal -->
<div id="h3_md_voucher_pengeluaran_fdo" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Voucher Pengeluaran</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_voucher_pengeluaran_fdo_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Voucher</th>
                            <th>Tanggal Transaksi</th>
                            <th>Via Bayar</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_voucher_pengeluaran_fdo_datatable = $('#h3_md_voucher_pengeluaran_fdo_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        ordering: false,
                        lengthChange: false,
                        order: [],
                        searching: false,
                        ordering: false,
                        ajax: {
                            url: "<?= base_url('api/md/h3/voucher_pengeluaran_fdo') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.invoice_number = $('#invoice_number_voucher_pengeluaran_pop_up').val();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'id_voucher_pengeluaran' },
                            { 
                                data: 'tanggal_transaksi',
                                render: function(data){
                                    return moment(data).format('DD/MM/YYYY');
                                }
                            },
                            { data: 'via_bayar' },
                            { 
                                data: 'nominal',
                                render: function(data){
                                    return accounting.formatMoney(data, 'Rp ', ',', '.');
                                },
                                className: 'text-right'
                            },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>