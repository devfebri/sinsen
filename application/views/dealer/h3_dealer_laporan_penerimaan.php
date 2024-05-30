<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
    <script src="<?= base_url("assets/vue/qs.min.js ") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/axios.min.js ") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue.min.js ") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/accounting.js ") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue-numeric.min.js ") ?>" type="text/javascript"></script>
    <script src="https://unpkg.com/vue-select@latest"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
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
                        <form class="form-horizontal" method="get" action='dealer/h3_dealer_laporan_penerimaan/export'>
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Periode</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" readonly id='periode_laporan'>
                                        <input type="hidden" id="start_date" name='start_date'>
                                        <input type="hidden" id="end_date" name='end_date'>
                                    </div>
                                    <label class="col-sm-2 control-label">Tipe Penerimaan</label>
                                    <div class="col-sm-3">
                                        <select class="form-control" name='type'>
                                            <option value="">-Choose-</option>
                                            <option value="Good">Good</option>
                                            <option value="Bad">Bad</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-2 col-sm-offset-2">
                                        <button v-if='auth.can_print' class="btn btn-flat btn-sm btn-primary">Laporan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var form_ = new Vue({
                el: '#form_',
                data: {
                    auth: <?= json_encode(get_user('h3_dealer_laporan_penerimaan')) ?>, 
                    loading: false,
                    errors: {},
                    indexPart: 0,
                },
                mounted: function(){
                    $('#periode_laporan').daterangepicker().on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                        $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
                        $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));

                    }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#start_date').val('');
                        $('#end_date').val('');
                    });
                }
            });
        </script>
  </section>
</div>