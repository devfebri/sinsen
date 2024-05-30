<!-- Modal -->
<div id="h3_md_part_kelompok_part" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Parts</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_part_kelompok_part_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Part</th>
                        <th>Nama Part</th>
                        <th>HET</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_part_kelompok_part_datatable = $('#h3_md_part_kelompok_part_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/part_kelompok_part') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.id_kelompok_part = form_.kelompok_part.id_kelompok_part;
                            d.selected_id_parts = _.map(form_.items, function(item){
                                return item.id_part;
                            });
                        }
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%' },
                        { data: 'id_part' },
                        { data: 'nama_part' },
                        { 
                            data: 'het',
                            render: function(data){
                                return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                            },
                            className: 'text-right'
                        },
                        { data: 'status' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>