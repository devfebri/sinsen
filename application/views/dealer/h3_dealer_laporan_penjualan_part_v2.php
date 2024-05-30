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
                <div class="box-header">
                    <div class="col-md-12 no-padding">
                        <button id='download-excel' class="btn btn-sm btn-flat btn-success">Download</button>
                    </div>
                </div>
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
                  });
                </script>
                <table id='laporan_gr_per_periode' class="table table-striped table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. NSC</th>
                            <th>Nama Konsumen</th>
                            <th>No. Polisi</th>
                            <th>Keterangan</th>
                            <th>Kode Part</th>
                            <th>Deskripsi Part</th>
                            <th>HET</th>
                            <th>Diskon</th>
                            <th>Diskon Promo</th>
                            <th>Qty</th>
                            <th>Total NSC</th>
                            <th>Total NJB</th>
                            <th>Total NSC (Claim/KPB)</th>
                            <th>Total NJB (Claim/KPB)</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tr>
                        <td colspan='11' class='text-right'>Grand Total</td>
                        <td colspan='2' id='total_nsc' class='text-right'></td>
                        <td colspan='2' id='total_nsc_khusus' class='text-right'></td>
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
            scrollX: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/dealer/laporan_penjualan_part') ?>",
                dataSrc: function(json){
                    axios.post('<?= base_url('api/dealer/laporan_penjualan_part/get_total_nsc')  ?>', Qs.stringify(json.input))
                    .then(function(res){
                        $('#total_nsc').text(
                            accounting.formatMoney(res.data.total, 'Rp ', 0, '.', ',')
                        );
                    });

                    axios.post('<?= base_url('api/dealer/laporan_penjualan_part/get_total_nsc_khusus')  ?>', Qs.stringify(json.input))
                    .then(function(res){
                        $('#total_nsc_khusus').text(
                            accounting.formatMoney(res.data.total, 'Rp ', 0, '.', ',')
                        );
                    });

                    return json.data;
                },
                type: "POST",
                data: function(d){
                    d.periode_filter_start = $('#periode_filter_start').val();
                    d.periode_filter_end = $('#periode_filter_end').val();
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { 
                    data: 'no_nsc',
                    render: function(data, type, row, meta){
                        sama_dengan_sebelumnya = false;
                        if(meta.row != 0){
                            data_sebelumnya = laporan_gr_per_periode.row(meta.row - 1).data();
                            sama_dengan_sebelumnya = row.no_nsc == data_sebelumnya.no_nsc;
                        }

                        if(sama_dengan_sebelumnya) return '';

                        if(data != null){
                            return data;
                        }
                        return '-';
                    }
                },
                { 
                    data: 'nama_customer',
                    render: function(data, type, row, meta){
                        sama_dengan_sebelumnya = false;
                        if(meta.row != 0){
                            data_sebelumnya = laporan_gr_per_periode.row(meta.row - 1).data();
                            sama_dengan_sebelumnya = row.no_nsc == data_sebelumnya.no_nsc;
                        }

                        if(sama_dengan_sebelumnya) return '';

                        return data;
                    }
                },
                { 
                    data: 'no_polisi',
                    render: function(data, type, row, meta){
                        sama_dengan_sebelumnya = false;
                        if(meta.row != 0){
                            data_sebelumnya = laporan_gr_per_periode.row(meta.row - 1).data();
                            sama_dengan_sebelumnya = row.no_nsc == data_sebelumnya.no_nsc;
                        }

                        if(sama_dengan_sebelumnya) return '';

                        if(data != null){
                            return data;
                        }
                        return '-';
                    },
                    width: '100px'
                },
                { 
                    data: 'keterangan',
                    render: function(data){
                        return data != null ? data : '-';
                    }
                },
                { data: 'id_part' },
                { data: 'nama_part' },
                { 
                    data: 'harga_saat_dibeli',
                    render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                    },
                    className: 'text-right'
                },
                { 
                    data: 'diskon_value',
                    render: function(data, type, row){
                        if(row.tipe_diskon == 'Percentage'){
                            return data + '%';
                        }else if(row.tipe_diskon == 'Value'){
                            return 'Rp ' + data;
                        }else{
                            return data;
                        }
                    }
                },
                { 
                    data: 'diskon_value_promo',
                    render: function(data, type, row){
                        if(row.tipe_diskon_promo == 'Percentage'){
                            return data + '%';
                        }else if(row.tipe_diskon_promo == 'Value'){
                            return 'Rp ' + data;
                        }else{
                            return data;
                        }
                    }
                },
                { 
                    data: 'qty',
                    render: function(data){
                        return accounting.format(data, 0, '.', ',');
                    },
                    className: 'text-right'
                },
                { 
                    data: 'total_nsc',
                    render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                    },
                    className: 'text-right'
                },
                { 
                    data: 'total_njb',
                    render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                    },
                    className: 'text-right'
                },
                { 
                    data: 'total_nsc_khusus',
                    render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                    },
                    className: 'text-right'
                },
                { 
                    data: 'total_njb_khusus',
                    render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                    },
                    className: 'text-right'
                },
            ],
        });

        $('button#download-excel').on('click', function(e){
            e.preventDefault();
            periode_filter_start = $('#periode_filter_start').val();
            periode_filter_end = $('#periode_filter_end').val();

            if(periode_filter_start == null && periode_filter_end == null){
                toastr.warning('Periode belum dipilih.');
                return;
            }

            query = new URLSearchParams({
                periode_filter_start: periode_filter_start,
                periode_filter_end: periode_filter_end,
            }).toString();
            window.location = 'dealer/h3_dealer_laporan_penjualan_part_v2/download_excel?' + query;
        });
    })
</script>