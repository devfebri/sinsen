<!-- Modal -->
<table id="h3_md_detail_ptm_ms_part_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Kode Tipe Besar</th>
            <th>Nama Tipe Besar</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<script>
$(document).ready(function() {
    h3_md_detail_ptm_ms_part_datatable = $('#h3_md_detail_ptm_ms_part_datatable').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "<?= base_url('api/md/h3/detail_ptm_ms_part') ?>",
            dataSrc: "data",
            type: "POST",
            data: function(d){
                d.id_part = form_.part.id_part;
            }
        },
        columns: [
            { data: null, orderable: false, width: '3%' }, 
            { data: 'tipe_marketing' },
            { data: 'deskripsi' },
        ],
    });

    h3_md_detail_ptm_ms_part_datatable.on('draw.dt', function() {
        var info = h3_md_detail_ptm_ms_part_datatable.page.info();
        h3_md_detail_ptm_ms_part_datatable.column(0, {
            search: 'applied',
            order: 'applied',
            page: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1 + info.start + ".";
        });
    });
});
</script>