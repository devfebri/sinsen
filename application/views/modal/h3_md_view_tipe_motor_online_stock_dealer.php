<!-- Modal -->
<div id="h3_md_view_tipe_motor_online_stock_dealer" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid bg-primary" style='padding: 5px 0px;  margin-bottom: 15px;'>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <span class='text-bold'>Tipe Motor</span>
                        </div>
                    </div>
                </div>
                <table id="h3_md_view_tipe_motor_online_stock_dealer_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tipe Motor</th>
                            <th>Deksripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_view_tipe_motor_online_stock_dealer_datatable = $('#h3_md_view_tipe_motor_online_stock_dealer_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        searching: false,
                        ajax: {
                            url: "<?= base_url('api/md/h3/tipe_motor_online_stock_dealer') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_part_for_view_tipe_motor = $('#id_part_for_view_tipe_motor').val();
                            }
                        },
                        columns: [
                            { data: 'index', orderable: false, width: '3%' },
                            { data: 'tipe_marketing' },
                            { data: 'deskripsi' },
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>