<!-- Modal -->
<div id="h3_md_kelompok_part_filter_part_sales_campaign_detail_cashback" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Kelompok Part</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_kelompok_part_filter_part_sales_campaign_detail_cashback_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
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
                    h3_md_kelompok_part_filter_part_sales_campaign_detail_cashback_datatable = $('#h3_md_kelompok_part_filter_part_sales_campaign_detail_cashback_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: "<?= base_url('api/md/h3/kelompok_part_filter_part_sales_campaign_detail_cashback') ?>",
                            dataSrc: "data",
                            type: "POST",
                            data: function(d){
                                d.id_kelompok_part_filter = $('#id_kelompok_part_filter_parts_sales_campaign_detail_cashback').val();
                                d.kategori = form_.sales_campaign.kategori;
                            }
                        },
                        columns: [
                            { data: 'id_kelompok_part' },
                            { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                        ],
                    });
                });
                </script>
            </div>
        </div>
    </div>
</div>