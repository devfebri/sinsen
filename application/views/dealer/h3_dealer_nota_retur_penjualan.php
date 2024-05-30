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
                            <th>No. Picking</th>
                            <th>Tgl Picking</th>
                            <th>No. SO</th>
                            <th>No. WO</th>
                            <th>Tgl WO</th>
                            <th>No. NSC</th>
                            <th>Nama Customer</th>
                            <th>No. Plat</th>
                            <th>Kode Part</th>
                            <th>Deskripsi Part</th>
                            <th>Lokasi</th>
                            <th>Kuantitas Part</th>
                            <th>Kuantitas Retur</th>
                            <th>Kuantitas Terpakai</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
                url: "<?= base_url('api/dealer/nota_retur_penjualan') ?>",
                dataSrc: 'data',
                type: "POST",
                data: function(d){
                    d.periode_filter_start = $('#periode_filter_start').val();
                    d.periode_filter_end = $('#periode_filter_end').val();
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' },
                { 
                    data: 'nomor_ps',
                    width: '150px'
                },
                { 
                    data: 'tanggal_ps',
                    render: function(data){
                        if(data != null){
                            return moment(data).format('DD/MM/YYYY');
                        }
                        return '-';
                    }
                },
                { data: 'nomor_so' },
                { 
                    data: 'id_work_order',
                    render: function(data){
                        if(data != null){
                            return data;
                        }
                        return '-';
                    }
                },
                { 
                    data: 'tgl_wo',
                    name: 'wo.created_at',
                    render: function(data){
                        if(data != null){
                            return moment(data).format('DD/MM/YYYY');
                        }
                        return '-';
                    }
                },
                { 
                    data: 'no_nsc',
                    render: function(data){
                        if(data != null){
                            return data;
                        }
                        return '-';
                    }
                },
                { data: 'nama_customer' },
                { 
                    data: 'no_polisi',
                    render: function(data){
                        if(data != null){
                            return data;
                        }
                        return '-';
                    },
                    width: '100px'
                },
                { data: 'id_part' },
                { data: 'nama_part' },
                { data: 'id_rak' },
                { 
                    data: 'kuantitas',
                    render: function(data){
                        return accounting.format(data, ',', '.');
                    },
                    className: 'text-right'
                },
                { 
                    data: 'kuantitas_return',
                    render: function(data){
                        return accounting.format(data, ',', '.');
                    },
                    className: 'text-right'
                },
                { 
                    data: 'kuantitas_terpakai',
                    render: function(data){
                        return accounting.format(data, ',', '.');
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
            window.location = 'dealer/h3_dealer_nota_retur_penjualan/download_excel?' + query;
        });
    })
</script>