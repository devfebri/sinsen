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
    $selisih_ssu = $this->m_admin->check_wrong_skema_kredit();
    ?><!-- /.row -->
	
    <div class="row">
      <div class="col-sm-6" id="divSalesFincContDP">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="login-box-msg" style="font-size: 16px;">Sales Finco Contribution By Gross Down Payment</h3>
            <div class="box-tools pull-right">
              <a class="btn btn-primary b tn-sm" target="_blank" href="<?= base_url('/dashboard/getSalesFincoByDP?download=y&tanggal=' . $tgl) ?>"><i class="fa fa-download"></i></a>
            </div>
          </div>
          <div class="box-body" style="min-height: 329px;">
            <table class="table table-bordered table-hovered table-striped" id="tabelSalesFincoByDP" style="font-size: 10pt; width:100%"></table>
          </div>
        </div>
      </div>    
	      <div class="col-sm-6" id="divSalesContDealerGroup">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="login-box-msg" style="font-size: 16px;">Sales Contribution By Dealer Group</h3>
            <div class="box-tools pull-right">
              <a class="btn btn-primary btn-sm" target="_blank" href="<?= base_url('/dashboard/getSalesByDealerGroup?download=y&tanggal=' . $tgl) ?>"><i class="fa fa-download"></i></a>
            </div>
          </div>
          <div class="box-body" style="min-height: 259px;">
            <!-- <div class="table-responsive">
              <table class="table table-bordered table-hovered table-striped" id="tabelSalesDealerGroup" style="font-size: 10pt; overflow-y: 'hidden';  overflow-x: 'hidden';"></table>
            </div> -->
            <table class="table table-bordered table-hovered table-striped" id="tabelSalesDealerGroup" style="font-size: 10pt;"></table>
          </div>
        </div>
      </div>
</div>

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
    console.log($('body').attr('class'));
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
    chartByFinco();
    chartByContribution();
    chartByCategory();
    // chartByContribution();
  }
  $(document).ready(function() {
    setGrafik('awal');
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
        $('#showDetailStok').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
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
        $('#chartByCategory').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
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
  var chartFinco;

  function chartByFinco() {
    values = {
      tanggal: '<?= $tgl ?>'
    }
    $.ajax({
      beforeSend: function() {
        $('#chartByFinco').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
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
    $.ajax({
      beforeSend: function() {
        $('#tabelSalesCompDistrict').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
      },
      url: "<?php echo site_url('dashboard/getSalesByDistrict') ?>",
      type: "POST",
      data: values,
      cache: false,
      success: function(response) {
        $('#tabelSalesCompDistrict').html(response);
        loadDatatables('tabelSalesCompDistrict');
        GetByDealerGroup();
      }
    })
  }

  function GetByDealerGroup() {
    $.ajax({
      beforeSend: function() {
        $('#tabelSalesDealerGroup').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
      },
      url: "<?php echo site_url('dashboard/getSalesByDealerGroup') ?>",
      type: "POST",
      data: values,
      cache: false,
      success: function(response) {
        $('#tabelSalesDealerGroup').html(response);
        loadDatatables('tabelSalesDealerGroup');
        // getStokMD()
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
        $('#chartByContribution').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
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
  }

  function getSalesFincoByDP() {
    $.ajax({
      beforeSend: function() {
        $('#tabelSalesFincoByDP').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
      },
      url: "<?php echo site_url('dashboard/getSalesFincoByDP') ?>",
      type: "POST",
      data: values,
      cache: false,
      success: function(response) {
        $('#tabelSalesFincoByDP').html(response);
        loadDatatables('tabelSalesFincoByDP');
        GetByDistrict();
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
    getSalesFincoByDP();
  })
</script>