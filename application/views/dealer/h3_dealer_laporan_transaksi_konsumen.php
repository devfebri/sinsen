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
    <script src="https://unpkg.com/vue-select@latest"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
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
            <div v-if='loading' class="overlay">
                <i class="fa fa-refresh fa-spin text-light-blue"></i>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="get" action='dealer/h3_dealer_laporan_stok_versi_kelompok/generate'>
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Periode</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" readonly id='periode_laporan'>
                                        <input type="hidden" id="start_date" name='start_date'>
                                        <input type="hidden" id="end_date" name='end_date'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1 col-sm-offset-2">
                                        <button v-if='auth.can_print' class="btn btn-flat btn-info" @click.prevent='set_preview'>Preview</button>
                                    </div>
                                    <div v-if='preview' class="col-sm-2">
                                        <a :href="'dealer/h3_dealer_laporan_transaksi_konsumen/generate?start_date=' + start_date + '&end_date=' + end_date + '&type=Excel'" class="btn btn-flat btn-primary">Download excel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div v-if='preview' class="row">
                            <div class="col-md-12">
                                <iframe :src="'dealer/h3_dealer_laporan_transaksi_konsumen/generate?start_date=' + start_date + '&end_date=' + end_date + '&type=Pdf'" frameborder="0" width='100%' height='1200px'></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var form_ = new Vue({
                el: '#form_',
                data: {
                    auth: <?= json_encode(get_user('h3_dealer_laporan_stok_versi_kelompok')) ?>, 
                    loading: false,
                    errors: {},
                    indexPart: 0,
                    start_date: '',
                    end_date: '',
                    preview: false,
                },
                methods: {
                    set_preview: function(){
                        this.preview = true;
                    }
                },
                mounted: function(){
                    $('#periode_laporan').daterangepicker({
                        autoUpdateInput: false
                    }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                        form_.start_date = picker.startDate.format('YYYY-MM-DD');
                        form_.end_date = picker.endDate.format('YYYY-MM-DD');
                    }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#start_date').val('');
                        $('#end_date').val('');
                    });
                },
                computed: {
                    readyForPreview: function(){
                        return this.start_date != '' && this.end_date != '';
                    }
                }
            });
        </script>
  </section>
</div>