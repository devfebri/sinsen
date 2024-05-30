<!-- Modal -->
<div id="h3_dealer_tipe_kendaraan_check_part_stock" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Tipe Kendaraan</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="" class="control-label">Filter Kategori</label>
                                    <select id="filter_tipe_kendaraan_check_part_stock" class="form-control">
                                        <option value="">-All-</option>
                                        <?php foreach($this->db->from('ms_kategori')->get()->result_array() as $row): ?>
                                        <option value="<?= $row['id_kategori'] ?>"><?= $row['kategori'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <script>
                        $(document).ready(function(){
                            $('#filter_tipe_kendaraan_check_part_stock').change(function(){
                                h3_dealer_tipe_kendaraan_check_part_stock_datatable.draw();
                            });
                        });
                        </script>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="" class="control-label">Filter Tahun Kendaraan</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="filter_tahun">
                                        <input type="hidden" id="filter_tahun_kendaraan">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $('#filter_tahun').datepicker({
                                    format: "yyyy",
                                    viewMode: "years", 
                                    minViewMode: "years"
                                })
                                .on('changeDate', function(e){
                                    $('#filter_tahun_kendaraan').val(e.target.value);
                                    h3_dealer_tipe_kendaraan_check_part_stock_datatable.draw();
                                });
                            });
                        </script>
                    </div>
                </div>
                <table id="h3_dealer_tipe_kendaraan_check_part_stock_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tipe Kendaraan</th>
                            <th>Tipe AHM</th>
                            <th>Deskripsi AHM</th>
                            <th>Tipe Customer</th>
                            <th>Tipe Part</th>
                            <th>CC</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        h3_dealer_tipe_kendaraan_check_part_stock_datatable = $('#h3_dealer_tipe_kendaraan_check_part_stock_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            order: [],
                            ajax: {
                                url: "<?= base_url('api/dealer/tipe_kendaraan_check_part_stock') ?>",
                                dataSrc: "data",
                                data: function(d) {
                                    d.id_part = form_.id_part;
                                    d.id_kategori = $('#filter_tipe_kendaraan_check_part_stock').val();
                                    d.filter_tahun_kendaraan = $('#filter_tahun_kendaraan').val();
                                },
                                type: "POST"
                            },
                            columns: [
                                { data: 'index', width: '1%', orderable: false },
                                { data: 'id_tipe_kendaraan' },
                                { data: 'tipe_ahm' },
                                { 
                                    data: 'deskripsi_ahm',
                                    render: function(data){
                                        if(data != null){
                                            return data;
                                        }
                                        return '-';
                                    }
                                },
                                { 
                                    data: 'tipe_customer',
                                    render: function(data){
                                        if(data != null){
                                            return data;
                                        }
                                        return '-';
                                    }
                                },
                                { data: 'tipe_part' },
                                { 
                                    data: 'cc_motor',
                                    render: function(data){
                                        if(data != null && data != ''){
                                            return data + " cc";
                                        }
                                        return '-';
                                    }
                                },
                                { data: 'action', width: '3%', className: 'text-center', orderable: false },
                            ],
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>