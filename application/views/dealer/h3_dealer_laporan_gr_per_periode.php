<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
    <script src="<?= base_url("assets/vue/qs.min.js ") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/axios.min.js ") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue.min.js ") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/accounting.js ") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue-numeric.min.js ") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
    <script>
        Vue.use(VueNumeric.default);
    </script>
    <section class="content-header">
        <h1><?php echo $title; ?></h1>
        <?= $breadcrumb ?>
    </section>
    <section class="content">
        <div id="form_" class="box box-default">
            <div class="box-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="control-label">Periode</label>
                                <input type="text" readonly id='periode' class='form-control'>
                                <input id='periode_filter_start' type="hidden" disabled>
                                <input id='periode_filter_end' type="hidden" disabled>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="" class="control-label">Tipe Referensi</label>
                                <select id="tipe_referensi_filter" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="packing_sheet_shipping_list">Shipping List</option>
                                    <option value="part_sales_work_order">Sales Order / Work Order</option>
                                    <option value="return_exchange_so">Return SO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                  $(document).ready(function(){
                    $('#periode').daterangepicker({
                        opens: 'left',
                        autoUpdateInput: false,
                        locale: {
                        format: 'DD/MM/YYYY'
                        }
                    }).on('apply.daterangepicker', function(ev, picker) {
                        $('#periode_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                        $('#periode_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                        laporan_gr_per_periode.draw();
                    }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#periode_filter_start').val('');
                        $('#periode_filter_end').val('');
                        laporan_gr_per_periode.draw();
                    });

                    $('#tipe_referensi_filter').on('change', function(){
                        laporan_gr_per_periode.draw();
                    })
                  });
                </script>
                <table id='laporan_gr_per_periode' class="table table-striped table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. GR</th>
                            <th>Tanggal GR</th>
                            <th>No. Referensi</th>
                            <th>Tipe Referensi</th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Qty Terima</th>
                            <th>Harga Beli</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tr>
                        <td colspan='7'></td>
                        <td id='qty_gr' class='text-right'></td>
                        <td></td>
                        <td id='total_harga_gr' class='text-right'></td>
                    </tr>
                </table>
            </div>
        </div>
  </section>
</div>
<script>
    $(document).ready(function(){
        laporan_gr_per_periode = $('#laporan_gr_per_periode').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            order: [],
            ajax: {
                url: "<?= base_url('api/dealer/laporan_gr_per_periode') ?>",
                dataSrc: function(json){
                    axios.post('<?= base_url('api/dealer/laporan_gr_per_periode/get_qty')  ?>', Qs.stringify(json.input))
                    .then(function(res){
                        $('#qty_gr').text(
                            accounting.format(res.data.kuantitas, 0, '.', ',')
                        );
                    });

                    axios.post('<?= base_url('api/dealer/laporan_gr_per_periode/get_total_harga')  ?>', Qs.stringify(json.input))
                    .then(function(res){
                        $('#total_harga_gr').text(
                            accounting.formatMoney(res.data.total_harga, 'Rp ', 0, '.', ',')
                        );
                    });

                    return json.data;
                },
                type: "POST",
                data: function(d){
                    d.periode_filter_start = $('#periode_filter_start').val();
                    d.periode_filter_end = $('#periode_filter_end').val();
                    d.tipe_referensi_filter = $('#tipe_referensi_filter').val();
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { data: 'id_good_receipt' },
                { 
                    data: 'tanggal_receipt',
                    render: function(data){
                        return moment(data).format('DD/MM/YYYY');
                    }
                },
                { data: 'id_reference' },
                { 
                    data: 'ref_type',
                    render: function(data){
                        if(data == 'packing_sheet_shipping_list'){
                            return 'Shipping List';
                        }else if(data == 'part_sales_work_order'){
                            return 'Sales Order / Work Order';
                        }else if(data == 'return_exchange_so'){
                            return 'Return SO';
                        }
                        return data;
                    }
                },
                { data: 'id_part' },
                { data: 'nama_part' },
                { 
                    data: 'qty',
                    className: 'text-right'
                },
                { 
                    data: 'harga_beli',
                    render: function(data){
                        return accounting.formatMoney(data, 'Rp ', ',', '.');
                    },
                    className: 'text-right'
                },
                { 
                    data: 'total_harga',
                    render: function(data){
                        return accounting.formatMoney(data, 'Rp ', ',', '.');
                    },
                    className: 'text-right'
                },
            ],
        });
    })
</script>