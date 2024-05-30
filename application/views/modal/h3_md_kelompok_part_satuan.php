<!-- Modal -->
<div id="h3_md_kelompok_part_satuan" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Kelompok Part</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_kelompok_part_satuan_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>Kelompok Part</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_kelompok_part_satuan_datatable = $('#h3_md_kelompok_part_satuan_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/kelompok_part_satuan') ?>",
                        dataSrc: "data",
                        type: "POST",
                        data: function(d){
                            d.selected_id_kelompok_part = _.map(form_.items, function(data){
                                return data.id_kelompok_part;
                            });
                        }
                    },
                    columns: [
                        { data: 'kelompok_part' },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>