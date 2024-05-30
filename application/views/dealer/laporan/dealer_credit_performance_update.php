<style type="text/css">
#spinner-div {
  text-align: center;
  font-size: 30px;
  padding-top: 500px;
  position: fixed;
  display: none;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 2;
}

#spinner-div-error {
  text-align: center;
  font-size: 30px;
  padding-top: 500px;
  position: fixed;
  display: none;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  text-color : red;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 2;
} 

.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
.vertical-text{
  writing-mode: lr-tb;
  text-orientation: mixed;
}
.rotate {
  -webkit-transform: rotate(-90deg);
  -moz-transform: rotate(-90deg);
}
#mySpan{
  writing-mode: vertical-lr; 
  transform: rotate(180deg);
}

#chart_cash_vs_credit {
        stacked: true;
        stackType:100%;
        height: 200px;
        min-height: 205px;
}

#overlay{	
  position: fixed;
  top: 0;
  z-index: 100;
  width: 100%;
  height:100%;
  display: none;
  background: rgba(0,0,0,0.6);
}
.cv-spinner {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;  
}
.spinner {
  width: 40px;
  height: 40px;
  border: 4px #ddd solid;
  border-top: 4px #2e93e6 solid;
  border-radius: 50%;
  animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
  100% { 
    transform: rotate(360deg); 
  }
}
.is-hide{
  display:none;
}

</style>



    <?php 
    if($set=="view"){
    ?>

<body onload="myFunction()">

<div id="spinner-div" class="pt-5">
  <div class="spinner-border text-primary" role="status"> Sedang Proses</div>
</div>

<div id="spinner-div-error" class="pt-5">
  <div class="spinner-border " role="status"> Sedang Proses</div>
</div>



<div class="content-wrapper">
  <section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>

  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">h1</li>
    <li class="">SLA Fincoy</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
  <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
            <div class="box-body">   
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-1 control-label">Segment</label> <div id="kota"></div>
                    <div class="col-sm-2">
                      <select class="form-control select2  pencarian_ajax target" id="id_segment_choose"  onChange="tampil_series(this.value)"  onchange="check_input_first()" >
                        <option value="">- Choose -</option>
                        <?php
                              foreach($segment as $row) { ?>
                            <option <?php if ($row->id_kategori == set_value('id_kategori') ) { echo 'selected'; }?>
                              value="<?=$row->id_kategori?>"><?=$row->kategori?></option>
                            <?php } ?>
                      </select>
                    </div>  

                    <label for="inputEmail3" class="col-sm-1 control-label">Kecamatan</label>
                  <div class="col-sm-2">
                  <select class="form-control select2 pencarian_ajax" name="id_kecamatan" id="id_kecamatan_choose"  onchange="check_input_first()">
                      <option value="">- Choose -</option>
                      <?php
                            foreach($kecamatan as $row) { ?>
                          <option <?php if ($row->id_kecamatan == set_value('id_kecamatan') ) { echo 'selected'; }?>
                            value="<?=$row->id_kecamatan?>"><?=$row->kecamatan?></option>
                          <?php } ?>
                    </select>
                  </div>
                  
                   
                  <label for="inputEmail3" class="col-sm-1 control-label">Tanggal </label>
                  <div class="col-sm-2">
                  <input type="text" class="form-control datepicker" value="" name="tanggal_awal" id="id_tanggal_choose_awal" readonly/>
                  </div> 

                  <div class="col-sm-2">
                  <input type="text" class="form-control datepicker pencarian_ajax" value="" name="tanggal_akhir" id="id_tanggal_choose_akhir"   onchange="check_input_first()" readonly/>
                  </div> 
              </div>  

              <div class="form-group">
               <label label for="inputEmail3" class="col-sm-1 control-label">Series</label>
                  <div class="col-sm-2">
                  <select class="form-control select2 pencarian_ajax get_series_ajax" name="id_series" id="id_series_ajax" onChange="tampil_tipe(this.value)"  onchange="check_input_first()" >
                      <option value="">- Choose - </option>
                    </select>
                  </div>  

                  <label for="inputEmail3" class="col-sm-1 control-label">Fincoy</label>
                  <div class="col-sm-2">
                  <select class="category_fincoy form-control select2 pencarian_ajax" name="id_finance_company[]"  id="id_fincoy_choose" onchange="check_input_first()" multiple>
                      <option value="">- Choose - </option>
                      <?php
                            foreach($fincoy as $row) { ?>
                          <option <?php if ($row->id_finance_company == set_value('id_finance_company') ) { echo 'selected'; }?>
                            value="<?=$row->id_finance_company?>"><?=$row->finance_company?></option>
                          <?php } ?>
                    </select>
                  </div>  
              </div> 

              <div class="form-group">

                <label for="inputEmail3" class="col-sm-1 control-label">Tipe</label>
                  <div class="col-sm-2">
                  <select class="form-control select2 pencarian_ajax" name="type" id="id_tipe_choose" onchange="check_input_first()">
                      <option value="">- Choose -</option>
                    </select>
                  </div>  

                  

                  <label for="inputEmail3" class="col-sm-1 control-label">DP (%)</label>
                  <div class="col-sm-2">
                  <select class="category_dp form-control select2 pencarian_ajax" name="type" id="id_choose_dp"  onchange="check_input_first()" multiple>
                      <option value="">- Choose - </option>
                      <option value="10">DP <=10%  </option>
                      <option value="1015">10 < DP<=15% </option>
                      <option value="1520">15 < DP<=20% </option>
                      <option value="20">DP >20% </option>
                    </select>
                  </div>  

                  <div class="col-sm-2">
                  </div>  

                  <div class="col-sm-2">
                     <!-- <button  type="button" onclick="set_pencarian_on_click()">Refresh </button>  -->
                  </div>  
                
              </div> 
            </div>   
            </form>
          </div>
        </div>
      </div>
  </div>
  </section>

  <section class="content">
            <div class="col-sm-12 col-md-3">
                <div class="small-box" style="background-color:#fff; color:white;  height: 350px " >
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Cash and Credit</h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_cash_vs_credit"></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-3">
                <div class="small-box" style="background-color:#fff; color:white;  height: 350px; ">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style=" font-size: 16px; ">Credit Share</h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_credit_share"></div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-12 col-md-3">
                <div class="small-box" style="background-color:#fff; color:white;  height: 350px; ">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style=" font-size: 16px; ">Avg. Rejected vs Approval</h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_reject_approval"></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-3">
                <div class="small-box" style="background-color:#fff; color:white;  height: 350px; ">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style=" font-size: 16px; ">Down Payment Comparrison</h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_down_payment_comparrison"></div>
                    </div>
                </div>
            </div>

            <!-- akhir row  -->
        <div class="row">   
            <div class="col-sm-12 col-md-4">
                <div class="small-box"" style="background-color:#fff; color:white;  height: 350px; ">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style=" font-size: 16px; font-size: 16px;">Avg. Rejected vs Approval perFincoy</h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_avg_reject_approval_per_fincoy"></div>
                    </div>
                </div>
            </div>

                        
            <div class="col-sm-12 col-md-4">
                <div class="small-box" style="background-color:#fff; color:white;  height: 350px; ">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Down Payment Comparrison perFincoy </h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_down_payment_per_fincoy"></div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12 col-md-4">
                <div class="small-box" style="background-color:#fff; color:white;  height: 350px; ">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Down Payment Comparrison based on Occuptation (M)</h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_down_payment_comparrison_occuptation_mtd"></div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-12 col-md-4">
                <div class="small-box" style="background-color:#fff; color:white;  height: 350px; ">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Order to Fincoy vs Rejected Rate (Daily)</h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_reject_by_oc_daily"></div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12 col-md-4">
                <div class="small-box"  style="background-color:#fff; color:white;  height: 350px; ">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Rejection by Down Payment peFincoy (M)</h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_reject_by_down_perfincoy_mtd"></div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12 col-md-4">
                <div class="small-box" style="background-color:#fff; color:white;  height: 350px; ">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Rejection by Occuption (M)</h3>
                    </div>
                    <div class="box-header">
                      <div id="chart_reject_by_oc"></div>
                    </div>
                </div>
            </div>

        </div>
            <!-- akhir row 3 -->
            
            <div class="col-sm-12 col-md-12">
                <div class="small-box" style="background-color:#fff; color:white">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Lead Time</h3>
                    </div>
                    <div class="box-header">
                    <table class="table">
                    <thead>
                      <tr>
                        <th colspan="2"></th>
                        <th colspan="4" style="text-align: center; background-color:bisque ">M-1</th>
                        <th colspan="4" style="text-align: center; background-color:lightgrey">M</th>
                      </tr>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Fincoy</th>
                        <th scope="col" style="text-align: center; background-color:lightsteelblue ">Total Average</th>
                        <th scope="col">Order to Approval/Rejection</th>
                        <th scope="col">PO to Delivery</th>
                        <th scope="col">Delivery to Disburse</th>
                        <th scope="col" style="text-align: center; background-color:lightsteelblue ">Total Average</th>
                        <th scope="col">Order to Approve Rejection</th>
                        <th scope="col">PO to Delivery</th>
                        <th scope="col">Delivery to Disburse</th>
                      </tr>
                    </thead>
                    <tbody class="table-body-append">
                    
                    </tbody>
                  </table>
                    </div>
                </div>
            </div>
  </section>
</div>

</body>

<script>

// $(document).ready(function() {
//     $(".pencarian_ajax").removeClass();
// });

  // function set_pencarian_on_click(){
  //   testing_inject();
  //     // window.location.reload();
  //   }

  // function testing_inject(){
  //  var tanggal_awal = '2023-05-04';
  //  var tanggal_akhir = '2023-05-04';
  //  var segment = '';
  //  var series = '';
  //  var tipe = '';
  //  var kecamatan = '';
  //  var fincoy = '';
  //  var dp = '';
  //   cash_vs_credit(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
  // }
</script>

<script>
function myFunction() {
  document.getElementById("id_tanggal_choose_awal").focus();
    alert('Tentukan tanggal Terlebih dahulu');
}
</script>

<script type="text/javascript">
		function tampil_series(series)
		{
    var pencarian_segment_ajax = document.getElementById("id_series_ajax");

      $.ajax({
      type: "GET",
      dataType: 'html',
      url: '<?php  echo base_url() . "dealer/dealer_credit_performance/get_data_series" ?>',
      data: {
          'tipe': series
      },
      success: function(data) {
        $('#id_series_ajax').html(data);
      },
      error: function() {
        $('#spinner-div').hide();  $('#spinner-div').hide();    alert("did not work");
      }
    })

		}

    function tampil_tipe(tipe) {

        $.ajax({
        type: "GET",
        dataType: 'html',
        url: '<?php  echo base_url() . "dealer/dealer_credit_funneling/get_data_tipe" ?>',
        data: {
            'tipe': tipe
        },
        success: function(data) {
          $('#id_tipe_choose').html(data);
        },
        error: function() {
          $('#spinner-div').hide();  $('#spinner-div').hide();    alert("did not work");
        }
      })
    }
	</script>


<script>
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>'
        )
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>'
        )
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>'
        )
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>



<script>
  
function check_input_first() {
var class_fincoy = $('.category_fincoy').val();
var class_dp = $('.category_dp').val();
var pencarian_tanggal_awal_ajax = document.getElementById("id_tanggal_choose_awal");
var tanggal_awal = pencarian_tanggal_awal_ajax.value;

var class_dp = $('.category_dp').val();
var pencarian_tanggal_awal_ajax = document.getElementById("id_tanggal_choose_awal");
var tanggal_awal = pencarian_tanggal_awal_ajax.value;
pencarian(class_fincoy,class_dp);
}

</script>

<script>
    function pencarian(class_fincoy,class_dp) {

    var pencarian_segment_ajax = document.getElementById("id_segment_choose");
    var segment = pencarian_segment_ajax.value;

    var pencarian_series_ajax = document.getElementById("id_series_ajax");
    var series = pencarian_series_ajax.value;

    var pencarian_tipe_ajax = document.getElementById("id_tipe_choose");
    var tipe = pencarian_tipe_ajax.value;

    // var pencarian_fincoy_ajax = document.getElementById("id_fincoy_choose");
    // var fincoy = pencarian_fincoy_ajax.value;

    // var pencarian_dp_ajax = document.getElementById("id_choose_dp");
    // var dp = pencarian_dp_ajax.value;

    var fincoy =class_fincoy;
    var dp = class_dp;

    var pencarian_kecamatan_ajax = document.getElementById("id_kecamatan_choose");
    var kecamatan = pencarian_kecamatan_ajax.value;

    var pencarian_tanggal_awal_ajax = document.getElementById("id_tanggal_choose_awal");
    var tanggal_awal = pencarian_tanggal_awal_ajax.value;

    var pencarian_tanggal_akhir_ajax = document.getElementById("id_tanggal_choose_akhir");
    var tanggal_akhir = pencarian_tanggal_akhir_ajax.value;

    // cash_vs_credit(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // credit_share(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // reject_vs_approval(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // down_payment_comparrison(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // avg_reject_approval_per_fincoy(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // down_payment_per_fincoy(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // reject_by_oc_perfincoy_mtd(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // chart_reject_by_oc_daily(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // down_payment_comparrison_occuptation_mtd(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // reject_by_down_perfincoy_mtd(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    // reject_by_oc(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
    
    lead_time(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
}

</script>


<script>
  // var loaded = false;
function cash_vs_credit(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal)
 {
  $('#spinner-div').show(); 

  // jQuery("#loader").show();

  //   if(loaded) return;

  $.ajax  ({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/cash_vs_credit/" ?>',
              data: {
                  'segment':segment,'series':series,'tipe':tipe,'fincoy':fincoy,'dp':dp,'kecamatan':kecamatan,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                cash_vs_credit_bar(data);
                credit_share(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
              },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
          // loaded = true;  
  }

function cash_vs_credit_bar(data){
  $('#spinner-div').hide(); 
    var cek=JSON.parse(data);
    var time = '';
    var cash = '';
    var credit = '';

    for (i=0; i<cek.cash_vs_credit.length; i++){
      time += cek.cash_vs_credit[i].Time + ", ";
      cash += cek.cash_vs_credit[i].Cash + ", ";   
      credit += cek.cash_vs_credit[i].Credit + ", ";
      }

          var temp_time = new Array();
          temp_time = time.split(",");
          var temp_cash = new Array();
          temp_cash = cash.split(",");
          var temp_credit = new Array();
          temp_credit = credit.split(",");

          var options = {
          series: [{
          name: 'Credit',
          data: temp_credit
        }, {
          name: 'Cash',
          data:temp_cash
        }],
          chart: {
          type: 'bar',
          height: 250,
          stacked: true,
          stackType: '100%'
        },
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],
        xaxis: {
          categories:temp_time,
        },
        fill: {
          opacity: 1
        },
        legend: {
          position: 'right',
          offsetX: 0,
          offsetY: 50
        },
        };
        $("#chart_cash_vs_credit").html("");
        var chart = new ApexCharts(document.querySelector("#chart_cash_vs_credit"), options);
        chart.render();
    };
</script>


<script>
function reject_vs_approval(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal)
 {
  // $('#spinner-div').show();
  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/reject_vs_approval/" ?>',
              data: {
                'segment':segment,'series':series,'tipe':tipe,'fincoy':fincoy,'dp':dp,'kecamatan':kecamatan,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                reject_vs_approval_bar(data);
                down_payment_comparrison(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);

              },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
  }

function reject_vs_approval_bar(data){
    var cek=JSON.parse(data);
    var time = '';
    var approve = '';
    var reject = '';

    for (i=0; i<cek.reject_vs_approval.length; i++){
      time += cek.reject_vs_approval[i].Time + ", ";
      approve += cek.reject_vs_approval[i].Approve + ", ";   
      reject += cek.reject_vs_approval[i].Reject + ", ";
      }

    var temp_time = new Array();
    temp_time = time.split(",");
    var temp_cash = new Array();
    temp_approve = approve.split(",");
    var temp_reject = new Array();
    temp_reject = reject.split(",");

          var options = {
          series: [{
          name: 'Reject',
          data: temp_reject
        }, {
          name: 'Approve',
          data: temp_approve
        }],
          chart: {
          type: 'bar',
          height: 250,
          stacked: true,
          stackType: '100%'
        },
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],
        xaxis: {
          categories:temp_time,
        },
        fill: {
          opacity: 1
        },
        legend: {
          position: 'right',
          offsetX: 0,
          offsetY: 50
        },
        };
        $("#chart_reject_approval").html("");
        var chart = new ApexCharts(document.querySelector("#chart_reject_approval"), options);
        chart.render();
    };
</script>


<script>
function credit_share(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal)
 {

  // $('#spinner-div').show();
  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/credit_share/" ?>',
              data: {
                'segment':segment,'series':series,'tipe':tipe,'kecamatan':kecamatan,'fincoy':fincoy,'dp':dp,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                credit_share_bar(data);
                reject_vs_approval(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
              },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
  }

function credit_share_bar(data){
  var credit_share=JSON.parse(data);
      var options = {
        series: credit_share,
        chart: {
        type: 'bar',
        height: 250,
        stacked: true,
        stackType: '100%'
      },
      plotOptions: {
        bar: {
          horizontal: false,
        },
      },
      stroke: {
        width: 1,
        colors: ['#fff']
      },
      title: {
      },
      xaxis: {
        categories: ['M-1','M'],
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val + ""
          }
        }
      },
      fill: {
        opacity: 1
      
      },
      legend: {
        position: 'top',
        horizontalAlign: 'left',
        offsetX: 40
      }
      };
      $("#chart_credit_share").html("");
        var chart = new ApexCharts(document.querySelector("#chart_credit_share"), options);
        chart.render();
    };
</script>

<script>
function down_payment_comparrison(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal)
 {
  // $('#spinner-div').show();
  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/down_payment_comparrison/" ?>',
              data: {
                'segment':segment,'series':series,'tipe':tipe,'kecamatan':kecamatan,'fincoy':fincoy,'dp':dp,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                down_payment_comparrison_bar(data);
                avg_reject_approval_per_fincoy(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
              },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
  }

function down_payment_comparrison_bar(data){
 
  var down_payment_comparrison=JSON.parse(data);
      var options = {
        series: down_payment_comparrison,
        chart: {
        type: 'bar',
        height: 250,
        stacked: true,
        stackType: '100%'
      }, 
      plotOptions: {
        bar: {
          horizontal: false,
        },
      },
      stroke: {
        width: 1,
        colors: ['#fff']
      },
      title: {
      },
      xaxis: {
        categories: ['M-1','M'],
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val + ""
          }
        }
      },
      fill: {
        opacity: 1
      
      },
      legend: {
        position: 'top',
        horizontalAlign: 'left',
        offsetX: 40
      }
      };
      $("#chart_down_payment_comparrison").html("");
        var chart = new ApexCharts(document.querySelector("#chart_down_payment_comparrison"), options);
        chart.render();
    };

</script>


<script>
function down_payment_comparrison_occuptation_mtd(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal){

  // $('#spinner-div').show();
  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/down_payment_comparrison_occuptation_mtd/" ?>',
              data: {
                'segment':segment,'series':series,'tipe':tipe,'kecamatan':kecamatan,'fincoy':fincoy,'dp':dp,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                down_payment_comparrison_occuptation_mtd_bar(data);
                chart_reject_by_oc_daily(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);

              },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
  }

function down_payment_comparrison_occuptation_mtd_bar(data){

  var down_payment_comparrison_occuptation_mtd=JSON.parse(data);  

  var pekerjaan = down_payment_comparrison_occuptation_mtd.pekerjaan;
  var bar = down_payment_comparrison_occuptation_mtd.bar;

  var pekerjaan = '';
  for (i=0; i<down_payment_comparrison_occuptation_mtd.pekerjaan.length; i++){
    pekerjaan += down_payment_comparrison_occuptation_mtd.pekerjaan[i].pekerjaan+ "," ;
  }

  var temp_pekerjaan  = new Array();
  temp_pekerjaan = pekerjaan.split(",");


    var options = {
        series: bar,
        chart: {
        type: 'bar',
        height: 250,
        stacked: true,
        stackType: '100%'
      },
      plotOptions: {
        bar: {
          horizontal: false,
        },
      },
      stroke: {
        width: 1,
        colors: ['#fff']
      },
      title: {
      },

      xaxis: {
        categories: temp_pekerjaan,
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val + ""
          }
        }
      },
      fill: {
        opacity: 1
      
      },
      legend: {
        position: 'top',
        horizontalAlign: 'left',
        offsetX: 40
      }
      };
      $("#chart_down_payment_comparrison_occuptation_mtd").html("");
        var chart = new ApexCharts(document.querySelector("#chart_down_payment_comparrison_occuptation_mtd"), options);
        chart.render();
    };


</script>

<script>
  
  function chart_reject_by_oc_daily(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal){
    // $('#spinner-div').show();
  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/reject_by_oc_daily/" ?>',
              data: {
                'segment':segment,'series':series,'tipe':tipe,'kecamatan':kecamatan,'fincoy':fincoy,'dp':dp,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                chart_reject_by_oc_daily_bar(data);
                reject_by_down_perfincoy_mtd(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);

              },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
        }

  function chart_reject_by_oc_daily_bar(data){
  var reject_daily=JSON.parse(data);
  var date = reject_daily.date;
  var bar = reject_daily.bar;

    var options = {
        series: bar,
        chart: {
        height: 250,
        type: 'line',
        dropShadow: {
          enabled: true,
          color: '#000',
          top: 18,
          left: 7,
          blur: 10,
          opacity: 0.2
        },
        toolbar: {
          show: false
        }
      },
      colors: ['#77B6EA', '#545454'],
      dataLabels: {
        enabled: true,
      },
      stroke: {
        curve: 'smooth'
      },
      grid: {
        borderColor: '#e7e7e7',
        row: {
          colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
          opacity: 0.5
        },
      },
      markers: {
        size: 1
      },
      xaxis: {
        categories:date,
        title: {
          text: 'Day'
        }
      },
      yaxis: {
        title: {
          text: 'Jumlah'
        },
        min: 0,
        max: 8
      },
      legend: {
        position: 'top',
        horizontalAlign: 'right',
        floating: true,
        offsetY: -25,
        offsetX: -5
      }
      };
      $("#chart_reject_by_oc_daily").html("");
      var chart = new ApexCharts(document.querySelector("#chart_reject_by_oc_daily"), options);
      chart.render();
    
    
      }
</script>



<script>

function down_payment_per_fincoy(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal){
  // $('#spinner-div').show();
  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/down_payment_per_fincoy/" ?>',
              data: {
                'segment':segment,'series':series,'tipe':tipe,'kecamatan':kecamatan,'fincoy':fincoy,'dp':dp,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                down_payment_per_fincoy_bar(data);
                down_payment_comparrison_occuptation_mtd(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
              },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
  }

  function down_payment_per_fincoy_bar(data){
    var down_payment_per_fincoy=JSON.parse(data);
    var fincoy = down_payment_per_fincoy.fincoy;
    var bar = down_payment_per_fincoy.bar;

    var options = {
          series:bar,
          chart: {
          type: 'bar',
          height: 250,
          stacked: true,
          stackType: '100%'
        },
        plotOptions: {
          bar: {
            horizontal: false,
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: {
        },
        xaxis: {
          categories:fincoy,
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + ""
            }
          }
        },
        fill: {
          opacity: 1
        
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
        };
        $("#chart_down_payment_per_fincoy").html("");
        var chart = new ApexCharts(document.querySelector("#chart_down_payment_per_fincoy"), options);
        chart.render();
  }
</script>

<script>
function avg_reject_approval_per_fincoy(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal){
  // $('#spinner-div').show();
  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/avg_reject_approval_per_fincoy/" ?>',
              data: {
                'segment':segment,'series':series,'tipe':tipe,'kecamatan':kecamatan,'fincoy':fincoy,'dp':dp,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                avg_reject_approval_per_fincoy_bar(data);
                down_payment_per_fincoy(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
              },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
  }

  function avg_reject_approval_per_fincoy_bar(data){
    var avg_reject_approval_per_fincoy=JSON.parse(data);
    var fincoy = avg_reject_approval_per_fincoy.fincoy;
    var bar = avg_reject_approval_per_fincoy.bar;

    var options = {
          series:bar,
          chart: {
          type: 'bar',
          height: 250,
          stacked: true,
          stackType: '100%'
        },
        plotOptions: {
          bar: {
            horizontal: false,
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: {
        },
        xaxis: {
          categories:fincoy,
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + ""
            }
          }
        },
        fill: {
          opacity: 1
        
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
        };
        $("#chart_avg_reject_approval_per_fincoy").html("");
        var chart = new ApexCharts(document.querySelector("#chart_avg_reject_approval_per_fincoy"), options);
        chart.render();

  }
</script>



<script>

function reject_by_down_perfincoy_mtd(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal){
  // $('#spinner-div').show();

  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/reject_by_down_perfincoy_mtd/" ?>',
              data: {
                'segment':segment,'series':series,'tipe':tipe,'kecamatan':kecamatan,'fincoy':fincoy,'dp':dp,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                reject_by_down_perfincoy_mtd_bar(data);
                // testinng ernesto
                lead_time(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
                // reject_by_oc(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
              },
             
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
  }

  function reject_by_down_perfincoy_mtd_bar(data){
    var reject_by_down_perfincoy_mtd_bar=JSON.parse(data);
    var fincoy = reject_by_down_perfincoy_mtd_bar.fincoy;
    var bar = reject_by_down_perfincoy_mtd_bar.bar;

    var options = {
          series:bar,
          chart: {
          type: 'bar',
          height: 250,
          stacked: true,
          stackType: '100%'
        },
        plotOptions: {
          bar: {
            horizontal: false,
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        title: {
        },
        xaxis: {
          categories:fincoy,
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + ""
            }
          }
        },
        fill: {
          opacity: 1
        
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
        };
        $("#chart_reject_by_down_perfincoy_mtd").html("");
        var chart = new ApexCharts(document.querySelector("#chart_reject_by_down_perfincoy_mtd"), options);
        chart.render();
  }
</script>

<script>
function reject_by_oc(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal){
  // $('#spinner-div').show();
  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/reject_by_oc/" ?>',
              data: {
                  'segment':segment,'series':series,'tipe':tipe,'fincoy':fincoy,'dp':dp,'kecamatan':kecamatan,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                reject_by_oc_bar(data);
                lead_time(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal);
              },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
}

function reject_by_oc_bar(data){

    var reject=JSON.parse(data);
    var pekerjaan = reject.data;
    var jumlah    = reject.categories;
        var options = {
          series: [{
          name: 'Jumlah',
          data: jumlah
        }],
          chart: {
          height: 250,
          type: 'bar',
          stackType: '100%'
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val + "";
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: pekerjaan,
          position: 'top',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0,
                opacityTo: 10,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
    
          }
        
        },
        title: {
          text: 'Pekerjaan',
          floating: true,
          offsetY: 330,
          align: 'center',
          style: {
            color: '#444'
          }
        }
        };

        $("#chart_reject_by_oc").html("");
        var chart = new ApexCharts(document.querySelector("#chart_reject_by_oc"), options);
        chart.render();
    };
</script>

    
<script>
function lead_time(segment,series,tipe,kecamatan,fincoy,dp,tanggal_akhir,tanggal_awal)
 {
  // $('#spinner-div').show();
  $.ajax({
              type: "POST",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_performance/lead_time_mounth/" ?>',
              data: {
                'segment':segment,'series':series,'tipe':tipe,'fincoy':fincoy,'dp':dp,'kecamatan':kecamatan,'tanggal_akhir':tanggal_akhir,'tanggal_awal':tanggal_awal
              },
              success: function(data) {
                $(".table-body-append").html(data);
              },   complete: function () {
                $('#spinner-div').hide();
            },
              error: function() {
                $('#spinner-div').hide();    alert("did not work");
              }
          });
  }
</script>



<?php }?>