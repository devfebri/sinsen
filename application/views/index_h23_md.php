<style>
   .canvasjs-chart-credit{
       background-color:white;
       color:white;
       display:block;
       z-index:-1000;
   }
 
</style>
<body onload="UeToday();UeMonth();UeKemarin();UeYtd();chartUnitEntry();chartKPB();">
<div class="content-wrapper">
  <section class="content-header">  
    <h1>
      Dashboard Main Dealer
      <!--<small>Control panel</small>-->
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>  
  <!-- Main content -->
  <section class="content">
      <div class="row">
          <div class="col-md-4">
            <div class="box box-warning" style="display:block;height:100%">
              <div class="box-header">
                <div class="box-tools pull-right">
                  <!--<a class="btn btn-default" href="javascript:void(0);" onclick="generate();"><i class="fa fa-copy"></i></a>    -->
                </div>
              </div>
              <div class="box-body box-warning" id="copyReport" style="min-height: 380px">
                <b>PT. SINAR SENTOSA PRIMATAMA</b> <br>
               TECHNICAL SERVICE DEPARTMENT <br>
                <p><?=tgl_indo(date('Y-m-d'))?></p>
              </div>
            </div>
          </div>
          <div class="col-md-8">
              <div class="row">
                  <div class="col-md-6">
                       <div class="small-box bg-blue">
                         <div class="inner">
                            <p id="ue_today" style="font-size:20px;font-weight:bold;"></p>
                             <p>&nbsp;</p>
                            <p>Total Unit Entry Hari ini</p>
                          </div>
                        </div>
                  </div>
                  <div class="col-md-6">
                       <div class="small-box bg-yellow">
                         <div class="inner">
                        <p id="ue_month" style="font-size:20px;font-weight:bold;"></p>
                            <p id="growth"></p>
                            <p>Total Unit Entry Bulan ini</p>
                          </div>
                        </div>
                  </div>
                  <div class="col-md-6">
                       <div class="small-box bg-green">
                         <div class="inner">
                             <p id="ue_kemarin" style="font-size:20px;font-weight:bold;"></p>
                             <p>&nbsp;</p>
                            <p>Total Unit Entry Kemarin</p>
                          </div>
                        </div>
                  </div>
                  <div class="col-md-6">
                       <div class="small-box bg-red">
                         <div class="inner">
                             <p id="ue_ytd" style="font-size:20px;font-weight:bold;"></p>
                            <p>&nbsp;</p>
                            <p>YTD Unit Entry 2021</p>
                          </div>
                        </div>
                  </div>
                  <div class="col-md-6">
                       <div class="small-box" style="background-color:#737a78;color:white;">
                         <div class="inner">
                             <p id="ue_monthM" style="font-size:20px;font-weight:bold;"></p>
                            <p>&nbsp;</p>
                            <p>Total Unit Entry Bulan Lalu </p>
                          </div>
                        </div>
                  </div>
                  <div class="col-md-6">
                       <div class="small-box bg-light-green" style="background-color:#25e65b;color:white;">
                         <div class="inner">
                             <?php date_default_timezone_set("Asia/Bangkok");?>
                           <p style="font-size:20px;font-weight:bold;"><?php echo tgl_indo(date('Y-m-d'))?></p>
                              <p><?php echo date('H:i')?></p> 
                            <p>Terakhir di Update </p>
                          </div>
                        </div>
                  </div>
              </div>
          </div>
      </div>
    <div class="row">
        <div class="col-md-4">
            <div class="box box-warning">
                <div class="box-header">
                     <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Unit Entry Promotion</h3>
                     <div class="box-tools pull-right">
                        <button class="btn btn-primary"><i class="fa fa-download"></i></button>
                        <button class="btn btn-warning" onclick="chartUnitEntry();"><i class="fa fa-refresh"></i></button>
                     </div>
                   
                </div>
                 <div class="box-body chat">
                    <div id="chartUe" style="margin: 0;height: 259px"></div>
                 </div>
            </div>
        </div>
         <div class="col-md-4">
            <div class="box box-warning">
                <div class="box-header">
                     <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Revenue</h3>
                      <div class="box-tools pull-right">
                        <button class="btn btn-primary"><i class="fa fa-download"></i></button>
                         <button class="btn btn-warning" onclick="chartRevenue();"><i class="fa fa-refresh"></i></button>
                     </div>
                </div>
                 <div class="box-body chat">
                    <div id="chartRevenue" style="margin: 0;height: 259px"></div>
                 </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-warning">
                <div class="box-header">
                     <i class="fa fa-graphic"></i>
                        <h3 class="login-box-msg" style="font-size: 16px;">Unit Entry KPB</h3>
                      <div class="box-tools pull-right">
                        <button class="btn btn-primary"><i class="fa fa-download"></i></button>
                        <button class="btn btn-warning" onclick="chartKPB();"><i class="fa fa-refresh"></i></button>
                     </div>
                </div>
                 <div class="box-body chat">
                    <div id="chartKPB" style="margin: 0;height: 259px"></div>
                 </div>
            </div>
        </div>
     </div> 
</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
var chartUe;
var chartRevenue;
var chartKpb;

function chartUnitEntry(dataPoints=null){
     values = {
      dataPoints: dataPoints
    }
    $.ajax({
        beforeSend: function() {
              $('#chartUe').html('<div display:flex;justify-content: center; align-items: center">Processing ...<img src="http://snekti.itpln.ac.id/assets/images/waiting.gif" style="display: block;margin-left: auto;margin-right: auto" width="200"></div>');
            },
        url: "<?php echo site_url('h23_api/grafik_ue_promotion') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success:function(response){
            // console.log(response.dataPoints);
            
            chart = new CanvasJS.Chart("chartUe", {
        	animationEnabled: true,
        	theme: "light1",
        
        	axisY:{
        		includeZero: true,
       	
        	},
        	toolTip: {
            shared: true
            },
        	legend:{
        		cursor: "pointer",
        		itemclick: toggleDataSeries
        	},
        	data: [{
        		type: "column",
        		 bevelEnabled: true,
        		name: "M-1",
        		indexLabel: "{y}",
        		color: "#3286f3", 
        		yValueFormatString: "#0.##",
        		dataPointWidth: 15,
        		showInLegend: true,
        		dataPoints: response.dataPoints1
        	},{
        		type: "column",
        		bevelEnabled: true,
        		name: "M",
        		indexLabel: "{y}",
        	    color: "#ff7a57", 
        	    yValueFormatString: "#0.##",
        	    dataPointWidth: 15,
        		showInLegend: true,
        		dataPoints: response.dataPoints
        	}]
        });
        chart.render();
        function toggleDataSeries(e){
	        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	    }
	    else{
		    e.dataSeries.visible = true;
	    }
	    chart.render();
        }
        }
    });
    


}


function chartRevenue(dataPoints=null){
    
     CanvasJS.addColorSet("greenShades",
                [
                "#3286f3",
                "#ff7a57",
                "grey",
                ]);
    
     values = {
      dataPoints: dataPoints
    }
    $.ajax({
        beforeSend: function() {
              $('#chartRevenue').html('<div display:flex;justify-content: center; align-items: center">Processing ...<img src="http://snekti.itpln.ac.id/assets/images/waiting.gif" style="display: block;margin-left: auto;margin-right: auto" width="200"></div>');
            },
        url: "<?php echo site_url('h23_api/grafik_revenue') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success:function(response){
        chart2 = new CanvasJS.Chart("chartRevenue", {
        	theme: "light1",
        	animationEnabled: true,
            colorSet: "greenShades",
        	data: [{
        	    bevelEnabled: true,
        		type: "pie",
        		indexLabel: "{y}",
        		indexLabelPlacement: "inside",
        		indexLabelFontColor: "white",
		        indexLabelFontSize: 11,
        	    yValueFormatString: "#,##0",
        		showInLegend: true,
        		indexLabelFontWeight: "bolder",
        		indexLabelOrientation: "horizontal",
        		legendText: "{label}",
        		dataPoints: response.dataPoints
        	}]
        });
        chart2.render();
        }
    });
}


function chartKPB(dataPoints=null){
     values = {
      dataPoints: dataPoints
    }
    $.ajax({
        beforeSend: function() {
              $('#chartKPB').html('<div display:flex;justify-content: center; align-items: center">Processing ...<img src="http://snekti.itpln.ac.id/assets/images/waiting.gif" style="display: block;margin-left: auto;margin-right: auto" width="200"></div>');
            },
        url: "<?php echo site_url('h23_api/grafik_kpb') ?>",
        type: "POST",
        data: values,
        cache: false,
        dataType: 'JSON',
        success:function(response){
            // console.log(response.dataPoints);
            
            chart3 = new CanvasJS.Chart("chartKPB", {
        	animationEnabled: true,
        	theme: "light1",
        
        	axisY:{
        		includeZero: true,
       	
        	},
        	toolTip: {
            shared: true
            },
        	legend:{
        		cursor: "pointer",
        		itemclick: toggleDataSeries
        	},
        	data: [{
        		type: "column",
        		bevelEnabled: true,
        		name: "M-1",
        		indexLabel: "{y}",
        		color: "#3286f3", 
        		yValueFormatString: "#0.##",
        		dataPointWidth: 15,
        		showInLegend: true,
        		dataPoints: response.dataPoints1
        	},{
        		type: "column",
        		bevelEnabled: true,
        		name: "M",
        		indexLabel: "{y}",
        	    color: "#ff7a57", 
        	    yValueFormatString: "#0.##",
        	    dataPointWidth: 15,
        		showInLegend: true,
        		dataPoints: response.dataPoints
        	}]
        });
        chart3.render();
        function toggleDataSeries(e){
	        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	    }
	    else{
		    e.dataSeries.visible = true;
	    }
	    chart3.render();
        }
        }
    });
    
}

    
</script>
<script>
    function UeToday(data=null){
    var hasil = document.getElementById("ue_today");
    $.ajax({
         beforeSend: function() {
               $('#ue_today').html('<tr><td colspan=12 style="font-size:12pt;text-align:center;width:100%;display:block">Processing...</td></tr>');
            },
        url: "<?php echo site_url('h23_api/total_ue_hari_ini') ?>",
        type: "POST",
        data: data,
        cache: false,
        dataType: 'JSON',
        success:function(response){
            $('#ue_today').html(response.data);
        }
    });
}

function UeMonth(data=null){
    var hasil = document.getElementById("ue_month");
    var hasilGrowth = document.getElementById("growth");
    $.ajax({
         beforeSend: function() {
               $('#ue_month').html('<tr><td colspan=12 style="font-size:12pt;text-align:center;width:100%;display:block">Processing...</td></tr>');
               $('#ue_monthM').html('<tr><td colspan=12 style="font-size:12pt;text-align:center;width:100%;display:block">Processing...</td></tr>');
               $('#growth').html('<tr><td colspan=12 style="font-size:12pt;text-align:center;width:100%;display:block">Processing...</td></tr>');
            },
        url: "<?php echo site_url('h23_api/total_ue_bulan_ini') ?>",
        type: "POST",
        data: data,
        cache: false,
        dataType: 'JSON',
        success:function(response){
            $('#ue_month').html(response.data);
            $('#ue_monthM').html(response.M1);
            $('#growth').html("Growth "+response.persent.toFixed(2) + " %");
        }
    });
}

function UeKemarin(data=null){
    
    $.ajax({
         beforeSend: function() {
               $('#ue_kemarin').html('<tr><td colspan=12 style="font-size:12pt;text-align:center;width:100%;display:block">Processing...</td></tr>');
            },
        url: "<?php echo site_url('h23_api/total_ue_kemarin') ?>",
        type: "POST",
        data: data,
        cache: false,
        dataType: 'JSON',
        success:function(response){
            $('#ue_kemarin').html(response.data);
        }
    });
    
}

function UeYtd(data=null){
    
    $.ajax({
         beforeSend: function() {
               $('#ue_ytd').html('<tr><td colspan=12 style="font-size:12pt;text-align:center;width:100%;display:block">Processing...</td></tr>');
            },
        url: "<?php echo site_url('h23_api/total_ue_ytd') ?>",
        type: "POST",
        data: data,
        cache: false,
        dataType: 'JSON',
        success:function(response){
            $('#ue_ytd').html(response.data);
        }
    });
    
}
</script>

<!--screenshot-->

     <script type="text/javascript">
(function (exports) {
    function urlsToAbsolute(nodeList) {
        if (!nodeList.length) {
            return [];
        }
        var attrName = 'href';
        if (nodeList[0].__proto__ === HTMLImageElement.prototype || nodeList[0].__proto__ === HTMLScriptElement.prototype) {
            attrName = 'src';
        }
        nodeList = [].map.call(nodeList, function (el, i) {
            var attr = el.getAttribute(attrName);
            if (!attr) {
                return;
            }
            var absURL = /^(https?|data):/i.test(attr);
            if (absURL) {
                return el;
            } else {
                return el;
            }
        });
        return nodeList;
    }

    function screenshotPage() {
        urlsToAbsolute(document.images);
        urlsToAbsolute(document.querySelectorAll("link[rel='stylesheet']"));
        var screenshot = document.documentElement.cloneNode(true);
        var b = document.createElement('base');
        b.href = document.location.protocol + '//' + location.host;
        var head = screenshot.querySelector('head');
        head.insertBefore(b, head.firstChild);
        screenshot.style.pointerEvents = 'none';
        screenshot.style.overflow = 'hidden';
        screenshot.style.webkitUserSelect = 'none';
        screenshot.style.mozUserSelect = 'none';
        screenshot.style.msUserSelect = 'none';
        screenshot.style.oUserSelect = 'none';
        screenshot.style.userSelect = 'none';
        screenshot.dataset.scrollX = window.scrollX;
        screenshot.dataset.scrollY = window.scrollY;
        var script = document.createElement('script');
        script.textContent = '(' + addOnPageLoad_.toString() + ')();';
        screenshot.querySelector('body').appendChild(script);
        var blob = new Blob([screenshot.outerHTML], {
           
        });
        const file = new File([blob], 'untitled', { type: blob.type })
        return file;
    }

    function addOnPageLoad_() {
        window.addEventListener('DOMContentLoaded', function (e) {
            var scrollX = document.documentElement.dataset.scrollX || 0;
            var scrollY = document.documentElement.dataset.scrollY || 0;
            window.scrollTo(scrollX, scrollY);
        });
    }

    function generate() {
        window.URL = window.URL || window.webkitURL;
        window.open(window.URL.createObjectURL(screenshotPage()));
       
    }
    exports.screenshotPage = screenshotPage;
    exports.generate = generate;
})(window);
</script>
<!---->