
<style type="text/css">

#loading-indicator {
  display: flex;
  justify-content: center;
  align-items: center;  
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background overlay */
  z-index: 9999; /* Ensure it's above other content */
}

#loading-spinner {
  /* Add your loading spinner styles here */
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

.table-container {
    width: 100%;
    max-height: 600px; /* Adjust the max height as needed */
    overflow-y: auto;
}

thead {
    position: sticky;
    top: 0;
    background-color: #fff;
}

thead th {
    z-index: 1;
    position: -webkit-sticky; /* For Safari */
}

tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

 #spinner-div {
  padding-top: 500px;
  font-size: 30px;
  position: fixed;
  display: none;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  text-align: center;
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

.label-credit-funneling{
  font-weight:bold; 
  font-size:26px;
  text-align: center;
}

</style>

<base href="<?php echo base_url(); ?>" />
    <?php 
    if($set=="view"){
    ?>

<body>

<div id="loading-indicator" style="display: none;">
  <div id="loading-spinner">
    Loading...
  </div>
</div>

  
<div id="spinner-div" class="pt-5">
  <div class="spinner-border text-primary" role="status"> Sedang Proses</div>
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
            <form class="form-horizontal" id="frm" method="post" action= "h1/sla_fincoy/downloadExcel" enctype="multipart/form-data">
            <div class="box-body">   
              <div class="form-group">
              
                <label for="inputEmail3" class="col-sm-1 control-label">Segment</label>
                    <div class="col-sm-2">
                      <select class="form-control select2  pencarian_segment_class" id="id_segment_choose"  onChange="tampil_series(this.value)" >
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
                  <select class="form-control select2 pencarian_ajax" name="id_kecamatan" id="id_kecamatan_choose" >
                      <option value="">- Choose -</option>
                      <?php
                            foreach($kecamatan as $row) { ?>
                          <option <?php if ($row->id_kecamatan == set_value('id_kecamatan') ) { echo 'selected'; }?>
                            value="<?=$row->id_kecamatan?>"><?=$row->kecamatan?></option>
                          <?php } ?>
                    </select>
                  </div>

                  <label for="inputEmail3" class="col-sm-1 control-label" data-toggle="tooltip" data-placement="M (Bulan Sekarang)">Tanggal (M) </label>
                  <div class="col-sm-2">
                  <input type="text" class="form-control datepicker pencarian_ajax" value="<?=$year=date('Y')?>-<?=$month=date('m')?>-01" name="tanggal" id="id_tanggal_choose_awal" readonly/>
                  </div> 
                           
                  <div class="col-sm-2">
                  <input type="text" class="form-control datepicker pencarian_ajax" value="<?=date('Y-m-d')?>" name="tanggal_akhir" id="id_tanggal_choose_akhir" readonly/>
                  </div> 
              </div>  

              <div class="form-group">
               <label label for="inputEmail3" class="col-sm-1 control-label">Series</label>
                  <div class="col-sm-2">
                  <select class="form-control select2 pencarian_ajax" name="id_series" id="id_series_ajax" onChange="tampil_tipe(this.value)"   >
                      <option value="">- Choose - </option>
                    </select>
                  </div>  

                  <label for="inputEmail3" class="col-sm-1 control-label">Fincoy</label>
                  <div class="col-sm-2">
                  <select class="category_fincoy form-control select2 pencarian_ajax" name="id_finance_company[]"  id="id_fincoy_choose"  multiple>
                      <option value="">- Choose - </option>
                      <?php
                            foreach($fincoy as $row) { ?>
                          <option <?php if ($row->id_finance_company == set_value('id_finance_company') ) { echo 'selected'; }?>
                            value="<?=$row->id_finance_company?>"><?=$row->finance_company?></option>
                          <?php } ?>
                    </select>
                  </div>  
                  
                  
                  <label for="inputEmail3" class="col-sm-1 control-label">Keterangan</label>
                  <div class="col-sm-2">
                  <select class="form-control select2 pencarian_ajax"  id="keterangan_choose"   >
                      <option value="">- Choose - </option>
                      <option value="on_going">Order - On Going</option>
                      <option value="ap_pending">Approved - Pending</option>
                      <option value="ap_schedule">Approved - Scheduled</option>
                      <option value="rejected">Rejected - </option>
                      <option value="delivered">Delivered - Not Yet Send Invoice </option>
                      <option value="invoice">Invoice Send - Not Yet Send Disbused </option>
                      <option value="disbused">Disbused --</option>
                    </select>
                  </div> 
              </div> 

              <div class="form-group">

                <label for="inputEmail3" class="col-sm-1 control-label">Tipe</label>
                  <div class="col-sm-2">
                  <select class="form-control select2 pencarian_ajax" name="id_tipe"  id="id_tipe_choose" >
                      <option value="">- Choose -</option>
                    </select>
                  </div>  

                  <label for="inputEmail3" class="col-sm-1 control-label">DP (%)</label>
                  <div class="col-sm-2">
                  <select class="category_dp form-control select2 pencarian_ajax" name="id_choose_dp[]" id="id_choose_dp"  multiple>
                      <option value="">- Choose - </option>
                      <option value="10">DP <=10%  </option>
                      <option value="1015">10 < DP<=15% </option>
                      <option value="1520">15 < DP<=20% </option>
                      <option value="20">DP >20% </option>
                    </select>
                  </div>  

                  <label for="inputEmail3" class="col-sm-1 control-label">Pencarian</label>
                  <div class="col-sm-2">
                  <input type="text" name="pencarian" id="pencarian_ajax" class="form-control pencarian_ajax">
                  </div>  

                  <div class="col-sm-2">
                     <button  type="button" class="btn btn-primary btn-sm btn-flat"  onclick = "fun()"> <i class="fa fa-search"></i> Cari </button> 
                     <button  type="button" class="btn btn-default btn-sm btn-flat"  onClick="window.location.reload();"> <i class="fa fa-refresh"></i> Refresh </button> 
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
            <div class="col-sm-3">
                <div class="small-box" style="background-color:#fff; color:white">
                    <div div class="box-header">
                        <i class="fa fa-graphic"></i>
                        <!-- <h3 class="login-box-msg" style="font-size: 16px;">Rejection by occuption</h3> -->
              </div>

          <div class="box-header">
            <div class="col-sm-12 col-md-12" style="padding:0; margin:0;">
                <div class="small-box" style="background-color: #2c2c2c; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id="id_order">0</div>
                        <p class="label-credit-funneling">Order</p>
                    </div>
                </div>
            </div>

            <!-- testing -->
                    
            <div class="col-sm-12 col-md-4" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#870404; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id="id_approved_count">0</div>
                        <p class="label-credit-funneling" >Approve</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-4" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#ef0707; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id="id_rejected_count">0</div>
                        <p class="label-credit-funneling" >Rejected</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-4" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#fb4e4e; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id="id_on_going_count">0</div>
                        <p class="label-credit-funneling">On Going</p>
                    </div>
                </div>
            </div>



            <div class="col-sm-12 col-md-4" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#d3ba06; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling"  id="id_delivered_count" >0</div>
                        <p class="label-credit-funneling">Delivered</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-4" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#e9d333; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id="id_schedule_count" >0</div>
                        <p class="label-credit-funneling">Scheduled</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-4" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#e9d85e; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling"  id="id_pending_count">0</div>
                        <p class="label-credit-funneling">Pending</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#0a7a03; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id="id_inv_send_count">0</div>
                        <p class="label-credit-funneling">Invoice Send</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#4ac742; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling"  id="id_inv_send_not_count" >0</div>
                        <p class="label-credit-funneling">Not set invoice Send</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#00746f; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id='id_disbursed_count'>0</div>
                        <p class="label-credit-funneling">Disbursed</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#5cefe9; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id="id_disbursed_not_yet_count">0</div>
                        <p class="label-credit-funneling">Not Yet Disbursed</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#898989; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id="id_sucess_rate">0 <span>%</span></div>
                        <p class="label-credit-funneling">Success Rate</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6" style="padding:0; margin:0;">
                <div class="small-box" style="background-color:#f33535; color:white">
                    <div class="inner">
                        <div class="label-credit-funneling" id="id_rejection_rate">0 <span>%</span></div>
                        <p class="label-credit-funneling">Rejection Rate</p>
                    </div>
                      </div>
                  </div>
                  </div>
                </div>
            </div>

            
            <div class="col-sm-9 col-md-9 box-table-funneling" style="padding: 0; margin: 0;">
    <div class="small-box" style="background-color: #fff; color: white;">
        <div class="box-header">
            <h3 class="login-box-msg" style="font-size: 16px;">Order Credit List</h3>
        </div>
        <div class="box-header">
            <div class="table-container">
                <table class="table table-bordered table-hover">
                    <thead class="tbl-product-body-head">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Order ID</th>
                            <th scope="col">No SPK</th>
                            <th scope="col">Nama Konsumen</th>
                            <th scope="col">Fincoy</th>
                            <th scope="col">Tipe Motor</th>
                            <th scope="col">Tanggal Order</th>
                            <th scope="col">Tanggal Order Approve/Rejected</th>
                            <th scope="col">Tanggal Delivery</th>
                            <th scope="col">Tanggal Invoice</th>
                            <th scope="col">No Invoice to (Fincoy)</th>
                            <th scope="col">Outstanding Days</th>
                            <th scope="col">Last Status</th>
                            <th scope="col">Next to Do</th>
                            <th scope="col">DP (%)</th>
                        </tr>
                    </thead>
                    <tbody class="tbl-product-body">
                        <!-- Add your table data here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

  </section>
</div>




<div class="overlay"></div>
</body>

<script>
    function fun() { 

  $("#loading-indicator").show();

  var pencarian_tanggal_awal_ajax = $("#id_tanggal_choose_awal").val();
  var pencarian_tanggal_akhir_ajax = $("#id_tanggal_choose_akhir").val();

  if (isValidDatePeriod(pencarian_tanggal_awal_ajax, pencarian_tanggal_akhir_ajax)) {

    setTimeout(function() {
      pencarian(pencarian_tanggal_awal_ajax, pencarian_tanggal_akhir_ajax);
      $("#loading-indicator").hide();
    }, 500); 

  } else {
    $("#id_tanggal_choose_awal").focus();
    alert("Pilih Priode Tanggal terlebih dahulu");
    
    $("#loading-indicator").hide();
  }

      } 
</script>


<script>

function isValidDatePeriod(start_date, end_date) {

  const startDateObj = new Date(start_date);
  const endDateObj = new Date(end_date);
  
  if (startDateObj.getMonth() !== endDateObj.getMonth()) {
    return false;
  }

  const oneDay = 24 * 60 * 60 * 1000; 
  const diffDays = Math.round(Math.abs((endDateObj - startDateObj) / oneDay));

  if (diffDays >= 31) {
    return false;
  }
  return true;
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
          alert("did not work");
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
            alert("did not work");
        }
      })
    }
</script>


<script>
    function pencarian(tanggal_awal, tanggal_akhir) {
    
    var tanggal_akhir = tanggal_akhir
    var tanggal_awal = tanggal_awal

    var pencarian_ajax = document.getElementById("pencarian_ajax");
    var search = pencarian_ajax.value;

    var pencarian_segment_ajax = document.getElementById("id_segment_choose");
    var segment = pencarian_segment_ajax.value;

    var pencarian_series_ajax = document.getElementById("id_series_ajax");
    var series = pencarian_series_ajax.value;

    var pencarian_tipe_ajax = document.getElementById("id_tipe_choose");
    var tipe = pencarian_tipe_ajax.value;

    var pencarian_keterangan_ajax = document.getElementById("keterangan_choose");
    var keterangan = pencarian_keterangan_ajax.value;


    var pencarian_fincoy_ajax = document.getElementById("id_fincoy_choose");
    var selectedOptions = Array.from(pencarian_fincoy_ajax.selectedOptions);

    var selectedValues = selectedOptions.map(function(option) {
      return "'" + option.value + "'";
    });

    var fincoy = selectedValues.join(',');

    var pencarian_kecamatan_ajax = document.getElementById("id_kecamatan_choose");
    var kecamatan = pencarian_kecamatan_ajax.value;
     
    var pencarian_dp_ajax = document.getElementById("id_choose_dp");

      var dp = [];

      for (var i = 0; i < pencarian_dp_ajax.options.length; i++) {
        var option = pencarian_dp_ajax.options[i];
        if (option.selected) {
          dp.push(option.value);
        }
      }


        $.ajax({
              type: "GET",
              dataType: 'html',
              url: '<?php echo base_url() . "dealer/dealer_credit_funneling/get_credit_funneling/" ?>',
              data: {
                  'search': search,'segment':segment,'series':series,'tipe':tipe,'fincoy':fincoy,'kecamatan':kecamatan,'keterangan':keterangan,'tanggal_awal':tanggal_awal,'tanggal_akhir':tanggal_akhir,'dp':dp,
              },
              success: function(data) {
                  var data_json = JSON.parse(data);
                  table = $('#example4').DataTable();  
                  $('.tbl-product-body').html(data_json.output);
                  $('#id_approved_count').html(data_json.approved);
                  $('#id_rejected_count').html(data_json.rejected);
                  $('#id_on_going_count').html(data_json.on_going);
                  $('#id_delivered_count').html(data_json.delivered);
                  $('#id_schedule_count').html(data_json.schedule);
                  $('#id_pending_count').html(data_json.pending);
                  $('#id_inv_send_count').html(data_json.inv_send);
                  $('#id_inv_send_not_count').html(data_json.inv_send_not);
                  $('#id_disbursed_count').html(data_json.disbursed);
                  $('#id_disbursed_not_yet_count').html(data_json.disbursed_not_yet);
                  $('#id_sucess_rate').html(data_json.sucess_rate);
                  $('#id_rejection_rate').html(data_json.rejection_rate);
                  $('#id_order').html(data_json.order);
              },
              complete: function () {
            },
              error: function() {
                  alert("did not work");
              }
          });
        }


      function segment() {
        var segment = $("#id_segment_choose option:selected").text();
        
            $.ajax({
            type: "GET",
            dataType: 'html',
            url: '<?php  echo base_url() . "dealer/dealer_credit_funneling/get_data_series" ?>',
            data: {
                'segment': segment
            },
            success: function(data) {
              $('#id_series_ajax').html(data);
              check_input_first();
            },
            error: function() {
                alert("did not work");
            }
          })
        }


 </script>



<?php }?>
