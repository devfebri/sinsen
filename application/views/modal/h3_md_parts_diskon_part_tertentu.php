<div id="h3_md_parts_diskon_part_tertentu" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_parts_diskon_part_tertentu_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Kelompok Part</th>
                            <th>HET</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                $(document).ready(function() {
                    h3_md_parts_diskon_part_tertentu_datatable = $('#h3_md_parts_diskon_part_tertentu_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/parts_diskon_part_tertentu') ?>",
                            dataSrc: "data",
                            type: "POST"
                        },
                        columns: [
                            { data: null, orderable: false, width: '3%' }, 
                            { data: 'id_part' },
                            { data: 'nama_part' },
                            { data: 'kelompok_part' },
                            { data: 'het' },
                            { data: 'action', orderable: false, width: '3%', className: 'text-center' }
                        ],
                    });
                    h3_md_parts_diskon_part_tertentu_datatable.on('draw.dt', function() {
                        var info = h3_md_parts_diskon_part_tertentu_datatable.page.info();
                        h3_md_parts_diskon_part_tertentu_datatable.column(0, {
                            search: 'applied',
                            order: 'applied',
                            page: 'applied'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = i + 1 + info.start + ".";
                        });
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>