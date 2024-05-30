<!-- Modal -->
<div id="modal-part" class="modal modalPart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Part</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari_kode_part" id="cari_kode_part" placeholder="Masukkan Kode Sparepart"/>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="cari_nama_part" id="cari_nama_part" placeholder="Masukkan Nama Part"/>
                            </div>
                            <div class="col-sm-4">
                               <button class="btn btn-primary btn-sm btn-flat" type="button" id="btn-cari_part"><span class="fa fa-search"></span>Search</button>
                            </div>
                        </div>
                    </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-condensed" id="datatable-part" style="width: 100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Part</th>
                    <th>Nama Part</th>
                    <th>Kelompok Vendor</th>
                    <th>HET</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            </table>
            <script>
            $(document).ready(function() {
                datatable_part = $('#datatable-part').DataTable({
                    processing: true,
                    serverSide: true,
                    searching:false,
                    order: [],
                    ajax: {
                        url: "<?= base_url('api/dealer/part_purchase_order') ?>",
                        dataSrc: "data",
                        data: function(d) {
                            d.po_type = form_.purchase.po_type;
                            d.kategori_po = form_.purchase.kategori_po;
                            d.produk = form_.purchase.produk;
                            d.cari_kode_part = $('#cari_kode_part').val();
                            d.cari_nama_part=$('#cari_nama_part').val();
                            d.id_part = _.chain(form_.parts)
                            .map(function(part){
                                return part.id_part;
                            })
                            .value();
                        },
                        type: "POST"
                    },
                    columns: [
                        { data: null, orderable: false, width: '3%'},
                        { data: 'id_part'},
                        { data: 'nama_part'}, 
                        { data: 'kelompok_vendor'},
                        { data: 'harga_dealer_user'},
                        { data: 'action', orderable: false, widht: '3%', className: 'text-center' }
                    ],
                });

                datatable_part.on('draw.dt', function() {
                    var info = datatable_part.page.info();
                    datatable_part.column(0, {
                        search: 'applied',
                        order: 'applied',
                        page: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1 + info.start + ".";
                    });
                });

                $('#btn-cari_part').click(function(e){
                    e.preventDefault();
                    datatable_part.draw();
                });
            });
            </script>
        </div>
        </div>
    </div>
</div>