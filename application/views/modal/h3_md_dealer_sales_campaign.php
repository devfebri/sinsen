<!-- Modal -->
<div id="h3_md_dealer_sales_campaign" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Dealer</h4>
            </div>
            <div class="modal-body">
                <table id="h3_md_dealer_sales_campaign_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No. </th>
                            <th>Kode Dealer</th>
                            <th>Nama Dealer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    $(document).ready(function () {
                        h3_md_dealer_sales_campaign_datatable = $("#h3_md_dealer_sales_campaign_datatable").DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/md/h3/dealer_sales_campaign') ?>",
                                dataSrc: "data",
                                type: "POST",
                                data: function (d) {
                                    d.selected_dealers = _.map(form_.sales_campaign_dealers, function (data) {
                                        return data.id_dealer;
                                    });
                                },
                            },
                            columns: [
                                { data: 'index', orderable: false, width: '3%' }, 
                                { data: 'kode_dealer_md' }, 
                                { data: 'nama_dealer' }, 
                                { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
