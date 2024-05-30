<?php 

function mata_uang3($a){

  if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);

    if(is_numeric($a) AND $a != 0 AND $a != ""){

      return number_format($a, 0, ',', '.');

    }else{

      return $a;

    }        

}

function bln($a){

  $bulan=$bl=$month=$a;

  switch($bulan)

  {

    case"1":$bulan="Januari"; break;

    case"2":$bulan="Februari"; break;

    case"3":$bulan="Maret"; break;

    case"4":$bulan="April"; break;

    case"5":$bulan="Mei"; break;

    case"6":$bulan="Juni"; break;

    case"7":$bulan="Juli"; break;

    case"8":$bulan="Agustus"; break;

    case"9":$bulan="September"; break;

    case"10":$bulan="Oktober"; break;

    case"11":$bulan="November"; break;

    case"12":$bulan="Desember"; break;

  }

  $bln = $bulan;

  return $bln;

}

?>



<base href="<?php echo base_url(); ?>" />

<div class="content-wrapper">

<!-- Content Header (Page header) -->

<section class="content-header">

  <h1>

    <?php echo $title; ?>    

  </h1>

  <ol class="breadcrumb">

    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    

    <li class="">H1</li>

    <li class="">Laporan</li>

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">

    

    



    <div class="box box-default">

      <div class="box-header with-border">        

        <div class="row">

          <div class="col-md-12">

            <form class="form-horizontal" action="h2/monitoring_utilisasi_h23/download" id="frm" method="post" enctype="multipart/form-data">

              <div class="box-body">                                                                              
                 <div class="col-md-12 container-fluid">
                    <div class="alert alert-info alert-dismissible " role="alert">
                  <strong>Informasi!</strong> Jika periode awal dan periode akhir tidak dipilih maka akan otomatis mengambil periode bulan saat ini.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="form-group">                                    

                  <label for="inputEmail3" class="col-sm-2 control-label">Pilih Periode</label>

                  <div class="col-sm-4">

                    <input placeholder="Periode Awal" type="text" autocomplete="off" name="started" id="tanggal1" required class="form-control">

                  </div>                  

                  <div class="col-sm-4">

                    <input placeholder="Periode Akhir" type="text" autocomplete="off" name="ended" id="tanggal2" required class="form-control">

                  </div>  
                  <div class="col-sm-2">
                    <a class="btn btn-flat btn-sm btn-primary" id="search_utilisasi">Search</a>
                  </div>
                  <!-- <div>
                      <button class="btn btn-flat btn-sm btn-primary"  id="reset"><span class="fa fa-trash"></span></button>
		              </div> -->
                </div>
                 <div class="col-md-12">

                  <div>
                      <button class="btn btn-flat btn-sm btn-warning"  id="view-graph">View Graph</button>
                      <button class="btn btn-flat btn-sm btn-danger"  id="view-table">View table</button>
                      <button class="btn btn-flat btn-sm btn-info"  id="view-graph2">Graph Konsistensi</button>
                  	<button class="btn btn-flat btn-sm btn-default" type="submit" name="generate" value='export_md' id="view-export">Export Utilisasi</button>
			              <button class="btn btn-flat btn-sm btn-warning" type="submit" name="generate" value='export_ks' id="view-export_ks">Export Konsistensi</button>
                  	<button class="btn btn-flat btn-sm btn-primary" type="submit" name="generate" value='export_dgi' id="view-export_ahm">Export DGI</button>
			              <button class="btn btn-flat btn-sm btn-success" type="submit" name="generate" value='export_sc' id="view-export_ahm">Export SC</button>
                    <button class="btn btn-flat btn-sm btn-default" type="submit" name="generate" value='export_ue' id="view-export">Export UE</button>
                    <button class="btn btn-flat btn-sm btn-primary" type="submit" name="generate" value='export_sl_pb' id="view-export">Export Part Inbound</button>                  
                  </div>

		</div>
                </div>
                </div>
               
            <div class="table-responsive" id="table">
            <table id="datatable_server" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Dealer</th>
                  <th>Nama Dealer</th>
                  <th>Apps (WO)</th>
                  <th>WO</th>
                  <th>Billing Process</th>
                  <th>Part Sales</th>
                  <th>Part Inbound</th>
                </tr>
              </thead>
            </table>
          </div>
          <div class="table-responsive" id="graph">
              <h3>Grafik Utilisasi H23</h3>
              <div id="chartRevenue" style="height: 400px; width: 100%;"></div>
          </div>
          <div class="table-responsive" id="graph2">
              <h3>Grafik Konsistensi H23</h3>
              <div id="chartRevenue2" style="height: 400px; width: 100%;"></div>
          </div>

          <script>
               $(document).ready(function(){
                  // $('#tanggal2').on("change", (function(){
                  //   if($('#tanggal1').val()!='' && $('#tanggal2').val()!=''){
                  //                     dataTable.draw();
                  //   }
                  // }));
                  $('#search_utilisasi').click(function(e){
                    dataTable.draw();
                  });
              
                  $('#reset').click(function(e){
                       e.preventDefault();
                        $('#tanggal1').val("");  
                        $('#tanggal2').val("");
                        dataTable.draw();
                  });
                });
          </script>
          <script>
              var chartRevenue;
              $(document).ready(function(){
                   $('#graph').hide();
	   	              $('#graph2').hide();

                   $('#view-graph').click(function(e){
                       e.preventDefault();
                      $('#table').hide();
                      $('#graph').show();
                      $('#graph2').hide();
                      chartRevenue();
                  });

                   $('#view-graph2').click(function(e){
                       e.preventDefault();
                      $('#table').hide();
                      $('#graph').hide();
                      $('#graph2').show();
                      chartRevenue2();
                  });

                   $('#view-table').click(function(e){
                       e.preventDefault();
                      $('#graph').hide();
                      $('#graph2').hide();
                      $('#table').show();
                  });
              });
            function chartRevenue(dataPoints=null){
                 CanvasJS.addColorSet("greenShades",
                            [
                            "#f3c300",
                            "#875692",
                            "#f38400",
                            "#a1caf1",
                            "#be0032",
                            "blue"
                            ]);
                
                $.ajax({
                    beforeSend: function() {
                          $('#chartRevenue').html('<div display:flex;justify-content: center; align-items: center"><img src="http://snekti.itpln.ac.id/assets/images/waiting.gif" style="display: block;margin-left: auto;margin-right: auto;margin-top:auto"  width="200"></div>');
                        toastr.info("Fetching data from server...");
                    },
                    url: "<?php echo site_url('h23_api/grafik_all_new') ?>",
                    type: "POST",
                  
                    dataType: 'JSON',
                    dataSrc: "data",
                    data: { 
                        'started':$('#tanggal1').val(),  
                        'ended': $('#tanggal2').val()
                    },
                    success:function(response){
                    toastr.info("Successfully loaded");
                    chart2 = new CanvasJS.Chart("chartRevenue", {
                    	theme: "dark1",
                    	animationEnabled: true,
                        colorSet: "greenShades",
                    	data: [{
                    	    cursor: "pointer",	
                    	    bevelEnabled: true,
                            type: "pie",
        		            startAngle: 240,
        		            yValueFormatString: "##0\"%\"",
        		            indexLabel: "{label} {y} ({x})",
        		            showInLegend: true,
        		            legendText: "{label}",
        		            click: function(e){
        		               toastr.info(e.dataPoint.label +" { Dealers: " + e.dataPoint.x + ", Persentage: "+ e.dataPoint.y + "% }" );
        		            },
                    		dataPoints: [
                    		    {
                    		        "label":"Apps (WO)",y:response.values[0]["total_wos_sc"],x:response.values[0]["wsc1"],exploded: true
                    		    },
                    		    {
                    		        "label":"Work Order",y:response.values[0]["total_wos"],x:response.values[0]["w1"],exploded: true
                    		    },
                    		     {
                    		        "label":"Billing Process",y:response.values[0]["total_billings"],x:response.values[0]["b1"],
                    		    },
                    		    {
                    		        "label":"Parts Sales",y:response.values[0]["total_pss"],x:response.values[0]["ps1"],
                    		    },
                    		    {
                    		        "label":"Parts Inbound",y:response.values[0]["total_inbounds"],x:response.values[0]["pi1"],
                    		    }
                    		       
                    		    ]
                    	}]
                    });
                    chart2.render();
                        
                    },
                    error:function(){
                        toastr.error("Opps something went wrong");
                    }
                });
            }

	function chartRevenue2(dataPoints=null){
                 CanvasJS.addColorSet("greenShades",
                            [
                            "#f3c300",
                            "#875692",
                            "#f38400",
                            "#a1caf1",
                            "#be0032",
                            "blue"
                            ]);
                
                $.ajax({
                    beforeSend: function() {
                          $('#chartRevenue2').html('<div display:flex;justify-content: center; align-items: center"><img src="http://snekti.itpln.ac.id/assets/images/waiting.gif" style="display: block;margin-left: auto;margin-right: auto;margin-top:auto"  width="200"></div>');
                        toastr.info("Fetching data from server...");
                    },
                    url: "<?php echo site_url('h23_api/grafik_konsistensi_h23') ?>",
                    type: "POST",
                  
                    dataType: 'JSON',
                    dataSrc: "data",
                    data: { 
                        'started':$('#tanggal1').val(),  
                        'ended': $('#tanggal2').val()
                    },
                    success:function(response){
                    toastr.info("Successfully loaded");
                    chart2 = new CanvasJS.Chart("chartRevenue2", {
                    	theme: "dark1",
                    	animationEnabled: true,
                        colorSet: "greenShades",
                    	data: [{
                    	    cursor: "pointer",	
                    	    bevelEnabled: true,
                            type: "pie",
        		            startAngle: 240,
        		            yValueFormatString: "##0\"%\"",
        		            indexLabel: "{label} {y} ({x})",
        		            showInLegend: true,
        		            legendText: "{label}",
        		            click: function(e){
        		               toastr.info(e.dataPoint.label +" { Dealers: " + e.dataPoint.x + ", Persentage: "+ e.dataPoint.y + "% }" );
        		            },
                    		dataPoints: [
                    		    {
                    		        "label":"Apps (WO)",y:response.values[0]["total_wos_sc"],x:response.values[0]["wsc1"],exploded: true
                    		    },
                    		    {
                    		        "label":"Work Order",y:response.values[0]["total_wos"],x:response.values[0]["w1"],exploded: true
                    		    },
                    		     {
                    		        "label":"Billing Process",y:response.values[0]["total_billings"],x:response.values[0]["b1"],
                    		    },
                    		    {
                    		        "label":"Parts Sales",y:response.values[0]["total_pss"],x:response.values[0]["ps1"],
                    		    },
                    		    {
                    		        "label":"Parts Inbound",y:response.values[0]["total_inbounds"],x:response.values[0]["pi1"],
                    		    }
                    		       
                    		    ]
                    	}]
                    });
                    chart2.render();
                        
                    },
                    error:function(){
                        toastr.error("Opps something went wrong");
                    }
                });
            }

          </script>
          <script>
  
            $(document).ready(function() {
               dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "searching":true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                "order": [],
                "lengthMenu": [
                  [10, 25, 50, 75, 100],
                  [10, 25, 50, 75, 100]
                ],
                "ajax": {
                  url: "<?php echo site_url('h23_api/fetch_all_module_new'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                  d.started = $('#tanggal1').val();
                  d.ended = $('#tanggal2').val();
                  },
                },
                "columnDefs": [
                 
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                ],
              });
              dataTable.on('draw', function() {
                var info = dataTable.page.info();
                dataTable.column(0, {
                    search: 'applied',
                    order: 'applied',
                    page: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + info.start + ".";
                });
              });
            });
          </script>

              </div><!-- /.box-body -->                           

            </form>

            <!-- <div id="imgContainer"></div> -->

          </div>

        </div>

      </div>

    </div><!-- /.box -->

</section>

</div>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    

    