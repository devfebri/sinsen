<label style="margin-right: 5px;">Kabupaten/Kota:
    <input type="text" class="form-control" readonly data-toggle='modal' data-target='#filter_kabupaten_kota_customer_h23_modal' id='kabupaten_kota_display'>
    <input type="hidden" id='id_kabupaten'>
</label>
<style>
    #kabupaten_kota_customer_h23_wrapper .row .col-sm-6, #kabupaten_kota_customer_h23_wrapper .row .col-sm-5{
        text-align: left;
    }
</style>
<!-- Modal -->
<div id="filter_kabupaten_kota_customer_h23_modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title text-left">Kabupaten / Kota</h4>
            </div>
            <div class="modal-body">
                <table id="kabupaten_kota_customer_h23" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kabupaten</th>
                            <th>Provinsi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <script>
                    $(document).ready(function() {
                        kabupaten_kota_customer_h23 = $('#kabupaten_kota_customer_h23').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "<?= base_url('api/md/h3/filter_kabupaten_kota_customer_h23') ?>",
                                dataSrc: "data",
                                type: "POST"
                            },
                            columns: [
                                { data: null, orderable: false, width: '3%' },
                                { data: 'kabupaten', className: 'text-left' },
                                { data: 'provinsi', className: 'text-left' }, 
                                { data: 'action', width: '3%', className: 'text-center', orderable: false }
                            ],
                        });

                        kabupaten_kota_customer_h23.on('draw.dt', function() {
                            var info = kabupaten_kota_customer_h23.page.info();
                            kabupaten_kota_customer_h23.column(0, {
                                search: 'applied',
                                order: 'applied',
                                page: 'applied'
                            }).nodes().each(function(cell, i) {
                                cell.innerHTML = i + 1 + info.start + ".";
                            });
                        });
                    });
                    function pilih_filter_kabupaten_kota_customer_h23(data){
                        $('#kabupaten_kota_display').val(data.kabupaten + ' - ' + data.provinsi);
                        $('#id_kabupaten').val(data.id_kabupaten);
                        customer_h23.draw();
                    }
                </script>
            </div>
        </div>
    </div>
</div>