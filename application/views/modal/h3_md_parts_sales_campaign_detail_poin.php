<!-- Modal -->
<div id="h3_md_parts_sales_campaign_detail_poin" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Part Sales Campaign Detail Poin</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4 align-middle">Kelompok Part</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input id="id_kelompok_part_filter_parts_sales_campaign_detail_poin" type="text" class="form-control" disabled/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-flat btn-primary" type="button" data-toggle="modal" data-target="#h3_md_kelompok_part_filter_part_sales_campaign_detail_poin">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <table id="h3_md_parts_sales_campaign_detail_poin_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>HET</th>
                            <th>Kelompok Part</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function () {
                        h3_md_parts_sales_campaign_detail_poin_datatable = $("#h3_md_parts_sales_campaign_detail_poin_datatable").DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/md/h3/parts_sales_campaign_detail_poin') ?>",
                                dataSrc: "data",
                                type: "POST",
                                data: function (d) {
                                    d.kategori = form_.sales_campaign.kategori;
                                    d.selected_id_parts = _.map(form_.sales_campaign_detail_poin, function (data) {
                                        return data.id_part;
                                    });
                                    d.id_kelompok_part_filter = $('#id_kelompok_part_filter_parts_sales_campaign_detail_poin').val();
                                },
                            },
                            columns: [{ data: "id_part" }, { data: "nama_part" }, { data: "het" }, { data: "kelompok_part" }, { data: "status" }, { data: "action", orderable: false, className: "text-center", width: "3%" }],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
