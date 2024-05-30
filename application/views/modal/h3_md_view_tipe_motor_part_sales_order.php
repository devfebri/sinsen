<!-- Modal -->
<div id="h3_md_view_tipe_motor_part_sales_order" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Tipe Motor</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_view_tipe_motor_part_sales_order_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Tipe Produksi</th>
                            <th>Tipe Marketing</th>
                            <th>Nama Motor</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                
                var h3_md_view_tipe_motor_part_sales_order_datatable;
                function drawing_tipe_motor_pso(){
                    $('#h3_md_view_tipe_motor_part_sales_order_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        ordering: false,
                        info: false,
                        searching: false,
                        paging: false,
                        lengthChange: false,
                        bDestroy:true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/view_tipe_motor') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_part_untuk_view_tipe_motor = $('#id_part_untuk_view_tipe_motor').val();
                            }
                        },
                        columns: [
                            { data: 'tipe_produksi' },
                            { data: 'tipe_marketing' },
                            { data: 'deskripsi' },
                        ],
                    });
                }
                </script>
            </div>
        </div>
    </div>
</div>