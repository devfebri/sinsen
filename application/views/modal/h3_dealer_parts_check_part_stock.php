<!-- Modal -->
<div id="h3_dealer_parts_check_part_stock" class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Parts</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid no-padding">
                    <div class="row">
                        <div class="col-sm-offset-8 col-sm-4 no-padding">
                            <label for="" class="control-label col-sm-4">Search</label>
                            <div class="col-sm-8">
                                <input id='search_filter' type="text" class="form-control" placeholder='Search'>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_dealer_parts_check_part_stock_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Nama Part Bahasa</th>
                            <th>Kelompok Vendor</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        h3_dealer_parts_check_part_stock_datatable = $('#h3_dealer_parts_check_part_stock_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            searching: false,
                            ajax: {
                                url: "<?= base_url('api/dealer/parts_check_part_stock') ?>",
                                dataSrc: "data",
                                type: "POST",
                                data: function (d) {
                                    d.id_tipe_kendaraan = form_.id_tipe_kendaraan;
                                    d.search.value = $('#search_filter').val();
                                }
                            },
                            columns: [
                                { data: 'index', width: '3%', orderable: false },
                                { data: 'id_part' },
                                { data: 'nama_part' },
                                { data: 'nama_part_bahasa' },
                                { data: 'kelompok_vendor' },
                                { 
                                    data: 'status', 
                                    orderable: false,
                                    render: function(data){
                                        if(data == 'A'){
                                            return 'Active';
                                        }else if(data == 'D'){
                                            return 'Discontinue';
                                        }
                                        return '-';
                                    }
                                },
                                { data: 'action', width: '3%', className: 'text-center', orderable: false }
                            ],
                        });

                        h3_dealer_parts_check_part_stock_datatable.on( 'search.dt', function (e) {
                            value = $('.dataTables_filter input').val();
                            form_.search_query = value;
                        });

                        $('#search_filter').on('keyup', _.debounce(function(e){
                            h3_dealer_parts_check_part_stock_datatable.draw();
                        }, 500));

                        // $('#search_filter').on('keypress', function(e){
                        //     if(e.which == 13){
                        //         e.preventDefault();
                        //         part_check_part_stock_datatable.draw();
                        //     }
                        // });
                    });
                </script>
            </div>
        </div>
    </div>
</div>