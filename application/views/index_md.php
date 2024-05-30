<!--body  onload="JavaScript:timedRefresh(10000);"-->
<script src="assets/panel/dist/js/canvas.min.js"></script>
<script src="assets/chartjs/Chart.bundle.min.js"></script>
<script type="text/javascript" src="assets/panel/dist/js/chartjs-plugin-labels.js"></script>
<script src="assets/highcharts/highcharts.js"></script>
<script src="assets/highcharts/modules/exporting.js"></script>

<style>
#tabelSalesFincoByDP_filter input {
    width: 100%
}

#tabelSalesCompDistrict_filter input {
    width: 70%
}

#tabelSalesDealerGroup_filter input {
    width: 70%
}
@media only screen and (max-width: 600px) {
    div#salin{
        width:96% !important;
        height:240px;
        margin-left:3px;
        margin-right:auto;
        
    }
    div#mpy{
        width:105% !important;
        margin-bottom:10px;
        margin-top:-30px;
        margin-left:-13px !important;
        /*margin-right:40px !important;*/
        height:240px;
    }
    div#terakhir-update{
       width:104% !important;
        margin-left:-15px !important;
        margin-right:auto;
    }
    div#terakhir-update2{
       width:96% !important;
        margin-left:1px !important;
        margin-right:auto;
    }
    div#sales-cat{
        margin-left:15px!important;
       
    }
    div#ssu{
        width:100% !important;
    }
} 
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="panel/home"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- <?php
          if ($_SESSION["setting"] == 'none') {
          ?>                  
    <div class="alert alert-warning alert-dismissable">
        <strong>Anda belum membuat setting untuk Dealer, silahkan hubungi Admin MD!</strong>
        <button class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>  
        </button>
    </div>
    <?php
          }
    ?> -->
        <!-- Small boxes (Stat box) -->
        <?php
    date_default_timezone_set('Asia/Jakarta');
    $tgl = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $jam = gmdate("H:i", time() + 60 * 60 * 7);
    $stk = $this->m_admin->get_data_dashboard($tgl);
    // $selisih_ssu = $this->m_admin->check_wrong_skema_kredit();
    $selisih_ssu['txt'] = '';
    ?>
        <!-- /.row -->
     
            
       
        <div class="row">
            <div class="col-sm-8"  style="padding-right:0px;">
                <div class="col-sm-9" style="padding:0px;">
                    <div class="col-sm-6" style="min-height: 288px;padding:0px;">
                        <div class="box box-warning" id="salin" >
                            <div class="box-header">
                                <div class="box-tools pull-right">
                                    <button class="btn btn-default" onclick="CopyToClipboard('copyReport')"><i
                                            class="fa fa-copy"></i></button>
                                </div>
                            </div>
                            <div class="box-body box-warning" id="copyReport" style="min-height: 225px">
                                <b>Sinar Sentosa Primatama</b> <br>
                                Sales Report S.E.E.D.S <br>
                                <?= mediumdate_indo($tgl, '-') ?>,  <?= $jam ?><br>
                                
                                <?= $stk['jml_hari'] ?>/<?= $stk['jml_bulan'] ?><br>
                                <?php if (is_array($stk['series_detail'])) : ?>
                                <?php foreach ($stk['series_detail'] as $ser) : ?>
                                <?= $ser['series'] . ' : ' . $ser['jml_hari'] . '/' . $ser['jml_bulan'] ?><br>
                                <?php endforeach ?>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="box box-warning" id="mpy">
                            <div class="box-body"  style="max-height: 245px;min-height:245px;margin-top:-10px">
                                <h3 style="font-size: 16px; text-align: center;">Marketing Progress Year on Year</h3>
                                <div id="tabelDistributionYoY"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-11" id="terakhir-update2" style="padding:0px;margin-left:auto;margin-right:auto;width:97.8%;max-width:97.8%;">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <p>
                                    <?php
                  $y = gmdate("d F Y", time() + 60 * 60 * 7);
                  $f = gmdate("H:i", time() + 60 * 60 * 7);
                  ?>
                                    <b>
                                        <?= $y ?> <br>
                                        <?= $f ?>
                                    </b>
                                </p>
                                <p>Terakhir di-Update</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-3" style="padding:0px;">
                    <div class="col-sm-12" id="terakhir-update">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?= $stk['jml_hari'] ?></h3>
                                <p>Penjualan Hari ini<?= $selisih_ssu['txt'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" id="terakhir-update">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?= $stk['jml_bulan'] ?></h3>
                                <p>Total Bulan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" id="terakhir-update">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><?= $stk['jml_kemarin'] ?></h3>
                                <p>Kemarin</p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="col-sm-4" id="sales-cat" style="padding:0px;margin-left:auto;margin-right:auto">
                <div class="col-sm-12" style="padding-left:0px;" id="divSalesByCat">
                    <div class="box box-warning">
                        <div class="box-header">
                            <i class="fa fa-graphic"></i>
                            <h3 class="login-box-msg" style="font-size: 16px;">Sales By Category</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-primary btn-sm" type="button" onclick="chartByCategory()"><i
                                        class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                        <div class="box-body chat">
                            <div id="chartByCategory" style="margin: 0 auto;height: 259px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <?php 
    $tgl = gmdate("Y-m-d", time() + 60 * 60 * 7);
    $ordIn = $this->m_admin->get_data_orderin_all($tgl);
     ?>
            <div class="col-sm-12 col-md-3">
                <div class="small-box" style="background-color:#0c3c52; color:white">
                    <div class="inner">
                        <div style="font-weight:bold; font-size:26px;"><?= $ordIn['walkin'].'/'.$ordIn['walkin_cum'] ?>
                        </div>
                        <p>Walk In/Cummulative</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-3">
                <div class="small-box" style="background-color:#3d34eb; color:white">
                    <div class="inner">
                        <div style="font-weight:bold; font-size:26px;">
                            <?= $ordIn['orderin'].'/'.$ordIn['orderin_cum'] ?></div>
                        <p>Order In/Cummulative</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-3">
                <div class="small-box" style="background-color:#917c3c; color:white">
                    <div class="inner">
                        <div style="font-weight:bold; font-size:26px;">
                            <?= $ordIn['success'].'/'.$ordIn['success_cum'].'/'.$ordIn['success_ratio'] ?></div>
                        <p>Success/Cummulative/Rate</p>
                    </div>
                </div>
            </div>


            <div class="col-sm-3">
                <div class="small-box" style="background-color:#ffbe45; color:white">
                    <div class="inner">
                        <div style="font-weight:bold; font-size:26px;">
                            <?= $ordIn['reject'].'/'.$ordIn['reject_cum'].'/'.$ordIn['reject_ratio'] ?></div>
                        <p>Failure/Cummulative/Rate</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-6" id="divSalesCompFinco">
                <div class="box box-warning">
                    <div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Sales Comparison By Finco</h3>
                    </div>
                    <div class="box-body chat">
                        <div id="chartByFinco" style="height: 259px; margin: 0 auto;"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6" id="divSalesContribution">
                <div class="box box-warning">
                    <div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Sales Contribution</h3>
                    </div>
                    <div class="box-body chat">
                        <canvas id="chartByContribution" style="height: 259px; margin: 0 auto;"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-6" id="divSalesFincContDP">
                <div class="box box-warning">
                    <div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Sales Finco Contribution By Gross Down
                            Payment</h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-primary b tn-sm" target="_blank"
                                href="<?= base_url('/dashboard/getSalesFincoByDP_new?download=y&tanggal=' . $tgl) ?>"><i
                                    class="fa fa-download"></i></a>
                        </div>
                    </div>
                    <div class="box-body" style="min-height: 329px;">
                        <table class="table table-bordered table-hovered table-striped" id="tabelSalesFincoByDP"
                            style="font-size: 10pt; width:100%"></table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6" id="divSalesContDealerGroup">
                <div class="box box-warning">
                    <div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Sales Contribution By Dealer Group</h3>
                        <div class="box-tools pull-right">
                            <?php /* <button class="btn btn-primary btn-sm" type="button" onclick="GetByDealerGroup()"><i class="fa fa-refresh"></i></button>       */ ?>                                     
                            <a class="btn btn-primary btn-sm" target="_blank"
                                href="<?= base_url('/dashboard/getSalesByDealerGroup?download=y&tanggal=' . $tgl) ?>"><i
                                    class="fa fa-download"></i></a>
                        </div>
                    </div>
                    <div class="box-body" style="min-height: 259px;">
                        <!-- <div class="table-responsive">
              <table class="table table-bordered table-hovered table-striped" id="tabelSalesDealerGroup" style="font-size: 10pt; overflow-y: 'hidden';  overflow-x: 'hidden';"></table>
            </div> -->
                        <table class="table table-bordered table-hovered table-striped" id="tabelSalesDealerGroup"
                            style="font-size: 10pt;"></table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Sales Dealer Group Year on Year</h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-primary btn-sm" target="_blank"
                                href="<?= base_url('/dashboard/getSalesByDealerGroupOfYear?download=y&tanggal=' . $tgl) ?>"><i class="fa fa-download"></i></a>
                        </div>
                    </div>
                    <div class="box-body" style="min-height: 259px;">
                        <table class="table table-bordered table-hovered table-striped" id="tabelSalesDealerGroupOfYear"
                            style="font-size: 10pt;"></table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12" id="divSalesContDistrict">
                <div class="box box-warning">
                    <div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Sales Comparison By District</h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-primary btn-sm" target="_blank"
                                href="<?= base_url('/dashboard/getSalesByDistrict?download=y&tanggal=' . $tgl) ?>"><i
                                    class="fa fa-download"></i></a>
                        </div>
                    </div>
                    <div class="box-body" style="min-height: 259px;">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hovered" id="tabelSalesCompDistrict"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- dihide dulu pesan pak andrew -->

        <!-- <div class="row">

            <div class="col-sm-4">
                <div class="box box-warning">
                    <div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Retail Sales YoY</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-primary btn-sm" type="button"
                                onclick="chartByCategoryOfYear()"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div>
                    <div class="box-body chat">
                        <div id="chartByCategoryOfYear" style="margin: 0 auto;height: 259px"></div>
                    </div>
                </div>
            </div>

        </div> -->

        <!-- <div class="row">
        <div class="col-sm-12">
          <div class="box box-warning">
            <div class="box-header">
              <i class="fa fa-graphic"></i>
              <h3 class="login-box-msg" style="font-size: 16px;">Sales & Stock Unit*</h3>
              <div class="box-tools pull-right">
                <a class="btn btn-primary btn-sm" target="_blank" href="<?= base_url('/dashboard/showDetailStok?download=y') ?>"><i class="fa fa-download"></i></a>
              </div>
            </div>
            <div class="box-body" style="min-height: 489px;">
              <center>
                <a href="#" class="btn btn-primary" id="btnShowDetailStok">Tampilkan Data</a>
              </center>
              <div class="table-responsive">
                <h5 id="waktuKlik"></h5>
                <table class="table table-bordered table-hovered table-striped " id="showDetailStok" style="text-align:center">
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>                                                  
       -->
</div>

<!-- <div class="row">
        <div class="chart-wrapper">
          <canvas id="doughnut-canvas4"></canvas>
        </div>
      </div> -->
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="mdlCheckLogin" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> PEMBERITAHUAN</h4>
            </div>
            <div class="modal-body">
                <p id="psn_pemberitahuan"></p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            </div>
        </div>

    </div>
</div>

<script src="assets/panel/plugins/graphic_new/highcharts.js"></script>
<!-- <script src="assets/panel/plugins/graphic_new/data.js"></script> -->
<!-- <script src="assets/panel/plugins/graphic_new/drilldown.js"></script> -->
<script src="assets/panel/plugins/graphic_new/exporting.js"></script>
<script src="assets/panel/plugins/graphic_new/export-data.js"></script>
<!-- <script src="assets/panel/plugins/graphic_new/accessibility.js"></script> -->
<script type="text/javascript" language="javascript" src="assets/panel/datatables/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="assets/panel/datatables/js/jquery.dataTables.js"></script>
<script>

$('.sidebar-toggle').click(function() {
    // console.log($('body').attr('class'));
    setGrafik();
})

$('.table_set1').DataTable({
    'paging': false,
    'bLengthChange': false,
    "bInfo": false,
    'searching': true,
    'ordering': true,
    'info': false,
    'scrollY': '301px',
    'scrollX': true,
    'scrollCollapse': true,
    'autoWidth': true,

})

function setGrafik(set = null) {
    if ($('body').hasClass('sidebar-collapse')) {
        $('#divSalesByCat').removeClass('col-sm-12');
        $('#divSalesByCat').addClass('col-sm-12');
        $('#divSalesCompFinco').removeClass('col-sm-6');
        $('#divSalesCompFinco').addClass('col-sm-6');
        $('#divSalesContribution').removeClass('col-sm-6');
        $('#divSalesContribution').addClass('col-sm-6');
    } else {
        $('#divSalesByCat').removeClass('col-sm-12');
        $('#divSalesByCat').addClass('col-sm-12');
        $('#divSalesCompFinco').removeClass('col-sm-6');
        $('#divSalesCompFinco').addClass('col-sm-6');
        $('#divSalesContribution').removeClass('col-sm-6');
        $('#divSalesContribution').addClass('col-sm-6');
    }
    // chartByCategory();
    getTotalDistribusiOfYear();

    window.setTimeout(() => {
        chartByContribution();
    }, 3000);

    window.setTimeout(() => {
        chartByFinco();
        // chartByFinco();
    }, 2000);
        
    window.setTimeout(() => {
        // chartByContribution();
        // chartByFinco();
        chartByCategory();
    }, 3000);
    // chartByCategoryOfYear();
    // chartByContribution();
}
$(document).ready(function() {
    setGrafik('awal');
    // console.log('1');
    $('body').addClass('sidebar-collapse');

    $("#btnShowDetailStok").click(function(e) {
        e.preventDefault();

        //simpan jam
        localStorage.setItem("klikStokDashboard", "<?php echo date('d F Y H:i:s') ?>");

        getStokMD();
        $(this).hide();
    });

});

function getStokMD() {
    $.ajax({
        beforeSend: function() {
            $('#showDetailStok').html(
                '<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
            // $('#showDetailStok').html('<tr><td colspan=12 style="font-size:12pt;text-align:center"><img src="<?php echo base_url() . "assets/giphy.gif" ?>"></td></tr>');
        },
        url: "<?php echo site_url('dashboard/showDetailStok') ?>",
        type: "POST",
        data: "",
        cache: false,
        success: function(response) {
            $('#showDetailStok').html(response);
            var waktuKlik = localStorage.getItem("klikStokDashboard");
            console.log(waktuKlik);
            loadDatatables('showDetailStok');
            $("#waktuKlik").text(waktuKlik);
        }
    })
}

function CopyToClipboard(id) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(id));
        range.select().createTextRange();
        document.execCommand("copy");

    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(id));
        window.getSelection().addRange(range);
        document.execCommand("copy");
        alert("Teks berhasil disalin")
    }
}
var chartCategory;

function chartByCategory(categories = null) {
    values = {
        tanggal: '<?= $tgl ?>',
        categories: categories
    }
    $.ajax({
        beforeSend: function() {
            $('#chartByCategory').html(
                '<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/chartByCategory') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success: function(response) {
            // console.log(response);        
            chartCategory = new Highcharts.chart('chartByCategory', {
                chart: {
                    type: 'column',
                    // height: 200,
                    spacingBottom: 15,
                    spacingTop: 20,
                    spacingLeft: 15,
                    spacingRight: 15,
                    // borderWidth: 1,
                    // borderColor: '#ddd'
                },
                title: {
                    text: null
                },
                legend: {
                    enabled: true,
                    padding: 0,
                    margin: 2
                },
                credits: {
                    enabled: true
                },
                xAxis: {
                    categories: response.categories,
                    // crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Unit'
                    }
                },
                series: response.series,
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                enabled: true
                            }
                        }
                    }]
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            crop: false,
                            overflow: 'none',
                            rotation: 270,
                            y: -30
                        }
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function() {
                                    chartByCategory(this.category);
                                }
                            }
                        },
                        dataLabels: {
                            align: 'right',
                            enabled: true,
                            // rotation: 270,
                            // x: 2,
                            // y: 0
                        }
                    }
                },
            });
        }
    })
}


var chartCategoryOfYear;

function chartByCategoryOfYear(categories = null) {
    values = {
        tanggal: '<?= $tgl ?>',
        categories: categories
    }
    $.ajax({
        beforeSend: function() {
            $('#chartByCategoryOfYear').html(
                '<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/chartByCategoryOfYear') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success: function(response) {
            // console.log(response);        
            chartCategoryOfYear = new Highcharts.chart('chartByCategoryOfYear', {
                chart: {
                    type: 'column',
                    // height: 200,
                    spacingBottom: 15,
                    spacingTop: 20,
                    spacingLeft: 15,
                    spacingRight: 15,
                    // borderWidth: 1,
                    // borderColor: '#ddd'
                },
                title: {
                    text: null
                },
                legend: {
                    enabled: true,
                    padding: 0,
                    margin: 2
                },
                credits: {
                    enabled: true
                },
                xAxis: {
                    categories: response.categories,
                    // crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Unit'
                    }
                },
                series: response.series,
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                enabled: true
                            }
                        }
                    }]
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            crop: false,
                            overflow: 'none',
                            rotation: 270,
                            y: -30
                        }
                    },
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function() {
                                    chartByCategoryOfYear(this.category);
                                }
                            }
                        },
                        dataLabels: {
                            align: 'right',
                            enabled: true,
                            // rotation: 270,
                            // x: 2,
                            // y: 0
                        }
                    }
                },
            });
        }
    })
}


var chartFinco;

function chartByFinco() {
    values = {
        tanggal: '<?= $tgl ?>'
    }
    $.ajax({
        beforeSend: function() {
            $('#chartByFinco').html(
                '<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/chartByFinco') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success: function(response) {
            // console.log(response);        
            chartFinco = new Highcharts.chart('chartByFinco', {
                chart: {
                    type: 'column',
                    spacingBottom: 15,
                    spacingTop: 20,
                    spacingLeft: 5,
                    spacingRight: 15,
                },
                title: {
                    text: null
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: response.categories,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Unit'
                    }
                },
                series: response.series,
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                enabled: false
                            }
                        }
                    }]
                },
                plotOptions: {
                    column: {
                        // dataLabels: {
                        //   enabled: true,
                        //   crop: false,
                        //   overflow: 'none',
                        //   rotation: 45
                        // }
                        dataLabels: {
                            enabled: true,
                            crop: false,
                            overflow: 'none',
                            rotation: 270,
                            y: -10,
                            x: 0,
                            style: {
                                fontSize: 9
                            }
                        }
                    },
                    series: {
                        cursor: 'pointer',
                        pointPadding: 0,
                    }
                },
            });
        }
    })
}


// var chartContribution;
// var values = {tanggal:'<?= $tgl ?>'};
// function chartByContribution() {
//   $.ajax({
//       beforeSend: function() {
//         $('#chartByContribution').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
//       },
//       url: "<?php echo site_url('dashboard/chartByContribution') ?>",
//       type:"POST",
//       data:values,
//       cache:false,
//       dataType:'JSON',
//       success:function(response){        
//         console.log(response);        
//          chartContribution = new Highcharts.chart('chartByContribution', {
//           chart: {
//               plotBackgroundColor: null,
//               plotBorderWidth: null,
//               plotShadow: false,
//               type: 'pie'
//             },
//             title: {
//                        text: null
//             },
//             tooltip: {
//                 pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
//             },
//             plotOptions: {
//                 pie: {
//                     // allowPointSelect: true,
//                     // cursor: 'pointer',
//                     // dataLabels: {
//                     //     distance:'-80%'
//                     // },
//                     // showInLegend: true
//                 }
//             },
//             series: [{
//                         name: 'Persen',
//                         colorByPoint: true,
//                         data: response
//                     }]
//          });
//       } 
//   })
// }
function loadDatatables(el) {
    scrolly = 250;
    ordering = false;
    order = [];

    if (el == 'tabelSalesDealerGroup') {
        var scrolly = 200;
    }
    if (el == 'tabelSalesDealerGroupOfYear') {
        var scrolly = 200;
    }
    if (el == 'tabelSalesFincoByDP') {
        var scrolly = 193;
    }
    if (el == 'tabelSalesCompDistrict') {
        var scrolly = 122;
    }

    //console.log(order);
    // console.log(scrolly);
    if (el == 'tabelSalesCompDistrict') {
        $('#' + el).DataTable({
            'paging': false,
            'bLengthChange': false,
            "bInfo": false,
            'searching': false,
            'ordering': ordering,
            'info': false,
            // 'scrollY': scrolly + 'px',
            // 'scrollX': true,
            'scrollCollapse': true,
            'autoWidth': true,

        })
    } else if (el == 'tabelSalesFincoByDP') {
        $('#' + el).DataTable({
            'paging': false,
            'bLengthChange': false,
            "bInfo": false,
            'searching': false,
            'ordering': ordering,
            'order': order,
            'info': false,
            // 'scrollY': scrolly + 'px',
            // 'scrollX': true,
            'scrollCollapse': true,
            //'autoWidth': true,

        })
    } else if (el == 'showDetailStok') {
        $('#' + el).DataTable({
            'paging': true,
            'bLengthChange': false,
            "bInfo": false,
            'searching': true,
            'ordering': true,
            'order': [11, 'desc'],
            'info': false,
            'scrollCollapse': true,
            'autoWidth': true,
            'pageLength': 5,

        })
    } else {
        $('#' + el).DataTable({
            'sScrollX': false,
            'paging': false,
            'bLengthChange': false,
            "bInfo": false,
            'searching': true,
            'ordering': ordering,
            'order': order,
            'info': false,
            'scrollY': scrolly + 'px',
            'scrollCollapse': false,

        })
    }
}

function GetByDistrict() {
    // sleep(8);
    $.ajax({
        beforeSend: function() {
            $('#tabelSalesCompDistrict').html(
                '<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/getSalesByDistrict') ?>",
        type: "POST",
        data: values,
        cache: false,
        success: function(response) {
            $('#tabelSalesCompDistrict').html(response);
            loadDatatables('tabelSalesCompDistrict');
            // GetByDealerGroup();
            // GetByDealerGroupOfYear();
        }
    })
}

function GetByDealerGroup() {
    $.ajax({
        beforeSend: function() {
            $('#tabelSalesDealerGroup').html(
                '<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/getSalesByDealerGroup') ?>",
        type: "POST",
        data: values,
        cache: false,
        success: function(response) {
            $('#tabelSalesDealerGroup').html(response);
            loadDatatables('tabelSalesDealerGroup');
            // getStokMD()
            GetByDealerGroupOfYear();
        }
    })
}

function GetByDealerGroupOfYear() {
    $.ajax({
        beforeSend: function() {
            $('#tabelSalesDealerGroupOfYear').html(
                '<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/getSalesByDealerGroupOfYear') ?>",
        type: "POST",
        data: values,
        cache: false,
        success: function(response) {
            $('#tabelSalesDealerGroupOfYear').html(response);
            loadDatatables('tabelSalesDealerGroupOfYear');
            // getStokMD()
            getSalesFincoByDP();
        }
    })
}

function createChart(id, type, options, response) {
    var data = {
        labels: response.labels,
        datasets: [{
            label: '',
            data: response.data,
            backgroundColor: response.backgroundColor
        }]
    };

    new Chart(document.getElementById(id), {
        type: type,
        data: data,
        options: options
    });
}

function chartByContribution() {
    $.ajax({
        beforeSend: function() {
            $('#chartByContribution').html(
                '<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/chartByContribution') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success: function(response) {
            createChart('chartByContribution', 'doughnut', {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                maintainAspectRatio: false,
                cutoutPercentage: 61,
                plugins: {
                    labels: {
                        render: 'value',
                        fontSize: 13,
                        fontStyle: '',
                        fontColor: '#2b2828',
                        fontFamily: '"Lucida Console", Monaco, monospace'
                    }
                }
            }, response);
        }
    })

    // chartByFinco();
}

function getSalesFincoByDP() {
    // sleep(5);
    // setTimeout(function () {
        $.ajax({
            beforeSend: function() {
                $('#tabelSalesFincoByDP').html(
                    '<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
            },
            url: "<?php echo site_url('dashboard/getSalesFincoByDP_new') ?>",
            type: "POST",
            data: values,
            cache: false,
            success: function(response) {
                $('#tabelSalesFincoByDP').html(response);
                loadDatatables('tabelSalesFincoByDP');
                GetByDistrict();
            }
        })
        
    // }, 5000);
}

function getTotalDistribusiOfYear() {
    // sleep(2);
    $.ajax({
        beforeSend: function() {
            $('#tabelDistributionYoY').html(
                '<span colspan=12 style="font-size:12pt;text-align:center">Processing...</span>');
        },
        url: "<?php echo site_url('dashboard/getTotalDistribusiOfYear') ?>",
        type: "POST",
        cache: false,
        success: function(response) {
            $('#tabelDistributionYoY').html(response);
        }
    })
}

// function downloadExcelStok() {
//   let i = '<i class="fa fa-spinner fa-pulse fa-fw"></i>';
//   values={download:true}
//   $.ajax({
//       beforeSend: function() {
//         $('#btnDownloadExcel').html(i);
//       },
//       url: "<?php echo site_url('dashboard/showDetailStok') ?>",
//       type:"POST",
//       data:values,            
//       cache:false,
//       success:function(response){                
//          $('#btnDownloadExcel').html('<i class="fa fa-download"></i>');
//          alert('OK');
//       } 
//   })
// }

$(window).load(function() {
    console.log('2');

    window.setTimeout(() => {
        // getSalesFincoByDP();
        GetByDealerGroup();
    }, 10000);
})
</script>