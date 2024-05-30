<!-- Modal -->
<div id="h3_md_parts_purchase_order_reguler_and_fix" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style='width: 80%;'>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Parts</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <!-- <div class="col-sm-4">
                            <div class="form-group">
                                <label for="" class="control-label">Kelompok Part</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly id='filter_kelompok_part'>
                                    <input type="hidden" id='filter_id_kelompok_part'>
                                    <div class="input-group-btn">
                                        <button id='btn_modal_filter_kelompok_part' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_purchase_order_kelompok_part_filter'><i class="fa fa-search"></i></button>
                                        <button id='btn_hapus_filter_kelompok_part' class="btn btn-flat btn-danger hidden"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label col-sm-4 align-middle">Kelompok Part</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input id='filter_kelompok_part' type="text" class="form-control" readonly>
                                        <input id='filter_id_kelompok_part' type="hidden">
                                        <div class="input-group-btn">
                                            <button class="btn btn-flat btn-primary" id="btn_modal_filter_kelompok_part" type="button" data-toggle='modal' data-target='#h3_md_purchase_order_kelompok_part_filter'>
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <button id='btn_hapus_filter_kelompok_part' class="btn btn-flat btn-danger hidden"><i class="fa fa-trash-o"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="control-label col-sm-4">Kode Part</label>
                            <div class="col-sm-8">
                                <input id='filter_kp' placeholder="Cari Kode Part" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="control-label col-sm-4">Nama Part</label>
                            <div class="col-sm-8">
                                <input id='filter_np' placeholder="Cari Nama Part" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <!-- <label for="" class="control-label col-sm-4">&nbsp;</label> -->
                            <div class="col-sm-8">
                                <a id='cari_filter_p' type="button" class="btn btn-warning">Cari</a>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-condensed" id="h3_md_parts_purchase_order_reguler_and_fix_datatable" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Part Number</th>
                            <th>Part Deskripsi</th>
                            <th>Kelompok Part</th>
                            <th>Qty On Hand</th>
                            <th>Qty Avs</th>
                            <th>Qty Intransit</th>
                            <th>HPP</th>
                            <th>HET</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <script>
                    // $(document).ready(function() {
                    var h3_md_parts_purchase_order_reguler_and_fix_datatable;

                    function drawing_po_part() {
                        h3_md_parts_purchase_order_reguler_and_fix_datatable = $('#h3_md_parts_purchase_order_reguler_and_fix_datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            bDestroy: true,
                            order: [],
                            searching: false,
                            "iDisplayLength": 5,
                            ajax: {
                                url: '<?= base_url('api/md/h3/parts_purchase_reguler_and_fix') ?>',
                                dataSrc: 'data',
                                type: 'POST',
                                data: function(d) {
                                    // d.selected_id_part = _.chain(app.parts)
                                    // .map(function(data){
                                    //     return data.id_part;
                                    // })
                                    // .value();
                                    d.id_part = _.chain(app.parts)
                                        .map(function(data) {
                                            return data.id_part;
                                        })
                                        .value();
                                    d.id_kelompok_part = $('#filter_id_kelompok_part').val();
                                    d.filter_kp = $('#filter_kp').val();
                                    d.filter_np = $('#filter_np').val();
                                    d.jenis_po = app.purchase.jenis_po;
                                    d.tanggal_po = app.purchase.tanggal_po;
                                    d.bulan = app.purchase.bulan;
                                    d.tahun = app.purchase.tahun;
                                    d.produk = app.purchase.produk;
                                }
                            },
                            columns: [{
                                    data: 'index',
                                    orderable: false,
                                    width: '3%'
                                },
                                {
                                    data: 'id_part'
                                },
                                {
                                    data: 'nama_part'
                                },
                                {
                                    data: 'kelompok_part'
                                },
                                {
                                    data: 'qty_on_hand',
                                    render: function(data) {
                                        return accounting.formatMoney(data, "", 0, ".", ",");
                                    },
                                    orderable: false
                                },
                                {
                                    data: 'qty_avs',
                                    render: function(data) {
                                        return accounting.formatMoney(data, "", 0, ".", ",");
                                    },
                                    orderable: false
                                },
                                {
                                    data: 'qty_in_transit',
                                    render: function(data) {
                                        return accounting.formatMoney(data, "", 0, ".", ",");
                                    },
                                    orderable: false
                                },
                                {
                                    data: 'harga',
                                    render: function(data) {
                                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                                    },
                                    className: 'text-right'
                                },
                                {
                                    data: 'harga_dealer_user',
                                    render: function(data) {
                                        return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                                    },
                                    className: 'text-right'
                                },
                                {
                                    data: 'action',
                                    orderable: false,
                                    widht: '3%',
                                    className: 'text-center'
                                }
                            ],
                        });
                    }
                    $(document).ready(function() {
                        $('#cari_filter_p').on('click', function(e) {
                            // h3_md_parts_purchase_order_reguler_and_fix_datatable.draw();
                            drawing_po_part();
                        });

                        $('#h3_md_parts_purchase_order_reguler_and_fix').on('keyup keypress', function(e) {
                            var keyCode = e.keyCode || e.which;
                            if (keyCode === 13) {
                                e.preventDefault();
                                return false;
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
<script>
    function pilih_filter_kelompok_parts(data) {
        $('#filter_kelompok_part').val(data.kelompok_part);
        $('#filter_id_kelompok_part').val(data.id_kelompok_part);

        $('#btn_modal_filter_kelompok_part').addClass('hidden');
        $('#btn_hapus_filter_kelompok_part').removeClass('hidden');
        // h3_md_parts_purchase_order_reguler_and_fix_datatable.draw();
        drawing_po_part();
    }

    $(document).ready(function() {
        $('#btn_hapus_filter_kelompok_part').click(function(e) {
            e.preventDefault();

            $('#filter_kelompok_part').val('');
            $('#filter_id_kelompok_part').val('');

            $('#btn_modal_filter_kelompok_part').removeClass('hidden');
            $('#btn_hapus_filter_kelompok_part').addClass('hidden');
            // h3_md_parts_purchase_order_reguler_and_fix_datatable.draw();
            drawing_po_part();
        });
    });
</script>
<?php $this->load->view('modal/h3_md_purchase_order_kelompok_part_filter'); ?>