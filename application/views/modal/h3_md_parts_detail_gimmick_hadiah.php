<!-- Modal -->
<div id="h3_md_parts_detail_gimmick_hadiah" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Part Sales Campaign Detail Diskon</h4>
        </div>
        <div class="modal-body">
            <table id="h3_md_parts_detail_gimmick_hadiah_datatable" class="table table-striped table-bordered table-hover table-condensed" style="width: 100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Part</th>
                        <th>Nama Part</th>
                        <th>HET</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <script>
            $(document).ready(function() {
                h3_md_parts_detail_gimmick_hadiah_datatable = $('#h3_md_parts_detail_gimmick_hadiah_datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/md/h3/part_detail_gimmick_hadiah') ?>",
                        dataSrc: "data",
                        type: "POST"
                    },
                    columns: [
                        { data: 'index', orderable: false, width: '3%' },
                        { data: 'id_part' },
                        { data: 'nama_part' },
                        { 
                            data: 'harga_dealer_user',
                            render: function(data){
                                if(data != null){
                                    return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                                }
                                return data;
                            }
                        },
                        { data: 'action', orderable: false, className: 'text-center', width: '3%' }
                    ],
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>