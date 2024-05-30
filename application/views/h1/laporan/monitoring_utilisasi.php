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


            <h4>Monitoring H1 Mobile Apps</h4>

            <form action="<?php echo base_url() ?>h1/monitoring_utilisasi" method="GET"> 
              <div class="form-group">
                <label>dari tanggal</label>
                <input type="date" name="tgl1" value="<?php echo (isset($_GET['tgl1'])) ? $_GET['tgl1'] : '' ?>" class="form-control">
              </div>
              <div class="form-group">
                <label>sampai tanggal</label>
                <input type="date" name="tgl2" value="<?php echo (isset($_GET['tgl2'])) ? $_GET['tgl2'] : '' ?>" class="form-control">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
              </div>
            </form>

            <table id="example5" class="table table-bordered table-hover dataTable no-footer">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Dealer</th>
                  <th>ID FLP</th>
                  <th>Nama</th>
                  <th>Jabatan</th>
                  <th>Total Hit Prospek</th>
                </tr>
              </thead>
              <tbody>
                <?php 

                if (isset($_GET['tgl1'])) {
                  $tgl1 = $this->input->get('tgl1');
                  $tgl2 = $this->input->get('tgl2');
                } else {
                  $tgl1 = date('Y-m-d');
                  $tgl2 = date('Y-m-d');
                }

                $no = 1;
                $sql = "SELECT
                           *
                        FROM
                          ms_karyawan_dealer 
                        WHERE
                          id_flp_md <> '' 
                          AND active = '1' 
                          AND id_jabatan IN ( 'JBT-071', 'JBT-072', 'JBT-073', 'JBT-074', 'JBT-063', 'JBT-064', 'JBT-065','JBT-035','JBT-053','JBT-111' ) ";
                foreach ($this->db->query($sql)->result() as $rw): 

                  $pr_sales = $this->db->query("SELECT id_prospek FROM tr_prospek WHERE id_karyawan_dealer='$rw->id_karyawan_dealer' and input_from='sc' and created_at between '$tgl1 00:59:59' and '$tgl2 23:59:59' ");

                  $pr_sc = $this->db->query("SELECT id_karyawan FROM log_prospek_sc WHERE id_karyawan='$rw->id_karyawan_dealer' and id_jabatan='JBT-035' and created_at between '$tgl1 00:59:59' and '$tgl2 23:59:59' ");

                  $pr_bm = $this->db->query("SELECT id_karyawan FROM log_prospek_sc WHERE id_karyawan='$rw->id_karyawan_dealer' and id_jabatan='JBT-053' and created_at between '$tgl1 00:59:59' and '$tgl2 23:59:59' ");

                  ?>
                  
                
                <tr>
                  <td><?php echo $no ?></td>
                  <td><?php echo get_data('ms_dealer','id_dealer',$rw->id_dealer,'nama_dealer') ?></td>
                  <td><?php echo $rw->id_flp_md ?></td>
                  <td><?php echo $rw->nama_lengkap ?></td>
                  <td><?php echo get_data('ms_jabatan','id_jabatan',$rw->id_jabatan,'jabatan') ?></td>
                  <td>
                    <?php 
                    if ($rw->id_jabatan == 'JBT-053') {
                      echo $pr_bm->num_rows();
                    } elseif($rw->id_jabatan == 'JBT-035') {
                      echo $pr_sc->num_rows();
                    } else {
                      echo $pr_sales->num_rows();
                    }


                     ?>
                  </td>
                </tr>
                <?php $no++; endforeach ?>
              </tbody>
            </table>
           

          </div>

        </div>

      </div>

    </div><!-- /.box -->


    <div class="box box-default">

      <div class="box-header with-border">        

        <div class="row">

          <div class="col-md-12">

            <form class="form-horizontal" action="h1/monitoring_utilisasi/download" id="frm" method="post" enctype="multipart/form-data">

              <div class="box-body">                                                                              

                <div class="form-group">                                    

                  <label for="inputEmail3" class="col-sm-2 control-label">Pilih Periode</label>

                  <div class="col-sm-3">

                    <input placeholder="Periode Awal" type="text" autocomplete="off" name="started" id="tanggal1" class="form-control">

                  </div>                  

                  <div class="col-sm-3">

                    <input placeholder="Periode Akhir" type="text" autocomplete="off" name="ended" id="tanggal2" class="form-control">

                  </div>  
                  <div class="col-sm-2">
                    <a class="btn btn-flat btn-sm btn-primary" id="search_utilisasi">Search</a>
                  </div>
                </div>
        <div class="col-md-12">                      
		<button class="btn btn-flat btn-sm btn-warning"  id="view-graph">View Graph</button>
    <a class="btn btn-flat btn-sm btn-danger"  id="view-table">View table</a>
		<button class="btn btn-flat btn-sm btn-info"  id="view-graph2">Graph Konsistensi</button>
		<button class="btn btn-flat btn-sm btn-success" type="submit" name="generate" value='export_ahm' id="view-export_ahm">Export X-Seeds</button>
    <button class="btn btn-flat btn-sm btn-primary" type="submit" name="generate" value='export_dgi_monitoring' id="generate_exel">Export DGI</button>
    <button class="btn btn-flat btn-sm btn-warning" type="submit" name="generate" value='export_prsp_spk' id="generate_exel2">Export Prospek VS SPK (AHM)</button>
    <button class="btn btn-flat btn-sm btn-danger" type="submit" name="generate" value='export_util_cons' id="generate_exel3">Export Utilisasi H1</button>
    <button class="btn btn-flat btn-sm btn-info" type="submit" name="generate" value='export_util_sl' id="generate_exel3">Utilisasi Shipping List</button>
    
  </div>

                </div> 


            <div class="table-responsive" id="table">
            <table id="datatable_server" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Dealer</th>
                  <th>Nama Dealer</th>
                  <th>Unit Inbound</th>
                  <th>Prospect</th>
                  <th>Prospect (Apps)</th>
                  <th>Dealing Process</th>
                  <th>Billing Process</th>
                  <th>Handle Leasing</th>
                  <th>Delivery Process</th>
                  <th>Document Handling</th>
                </tr>
              </thead>
            </table>
          </div>

          <div class="table-responsive" id="graph">
              <h3>Grafik Utilisasi H1</h3>
              <div id="chartRevenue" style="height: 400px; width: 100%;"></div>
          </div>
          <div class="table-responsive" id="graph2">
              <h3>Grafik Konsistensi H1</h3>
              <div id="chartRevenue2" style="height: 400px; width: 100%;"></div>
          </div>

          <script>
              //  $(document).ready(function(){
              //     $('#tanggal2').on("change", (function(){
              //       dataTable.draw();
              //     }));
              //   });
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
                      $(document).ready(function() {
                        dataTable.draw();
                      });
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
                            "#c2b280",
                            "#848482",
                            "#dd4b39",
                            ]);
                
                $.ajax({
                    beforeSend: function() {
                          $('#chartRevenue').html('<div display:flex;justify-content: center; align-items: center"><img src="http://snekti.itpln.ac.id/assets/images/waiting.gif" style="display: block;margin-left: auto;margin-right: auto;margin-top:auto"  width="200"></div>');
                        },
                    url: "<?php echo site_url('h23_api/grafik_monitoring_h1') ?>",
                    type: "POST",
                  
                    dataType: 'JSON',
                    dataSrc: "data",
                    data: { 
                        'started':$('#tanggal1').val(),  
                        'ended': $('#tanggal2').val()
                    },
                    success:function(response){
                    chart2 = new CanvasJS.Chart("chartRevenue", {
                      theme: "dark2",
                      animationEnabled: true,
                        colorSet: "greenShades",
                      data: [{
                            type: "pie",
                        startAngle: 240,
                        yValueFormatString: "##0\"%\"",
                        indexLabel: "{label} {y}",
                        showInLegend: true,
                        legendText: "{label}",
                        dataPoints: response.dataPoints
                      }]
                    });
                    chart2.render();
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
                            "#c2b280",
                            "#848482",
                            "#dd4b39",
                            ]);
                
                $.ajax({
                    beforeSend: function() {
                          $('#chartRevenue2').html('<div display:flex;justify-content: center; align-items: center"><img src="http://snekti.itpln.ac.id/assets/images/waiting.gif" style="display: block;margin-left: auto;margin-right: auto;margin-top:auto"  width="200"></div>');
                        },
                    url: "<?php echo site_url('h23_api/grafik_monitoring_konsistensi_h1') ?>",
                    type: "POST",
                  
                    dataType: 'JSON',
                    dataSrc: "data",
                    data: { 
                        'started':$('#tanggal1').val(),  
                        'ended': $('#tanggal2').val()
                    },
                    success:function(response){
                    chart2 = new CanvasJS.Chart("chartRevenue2", {
                      theme: "dark2",
                      animationEnabled: true,
                        colorSet: "greenShades",
                      data: [{
                            type: "pie",
                        startAngle: 240,
                        yValueFormatString: "##0\"%\"",
                        indexLabel: "{label} {y}",
                        showInLegend: true,
                        legendText: "{label}",
                        dataPoints: response.dataPoints
                      }]
                    });
                    chart2.render();
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
                  url: "<?php echo site_url('h23_api/grafik_all_h1'); ?>",
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
            
            $(document).ready(function() {
                  $('#search_utilisasi').click(function(e){
                    // dataTable.clear().destroy();
                            // e.preventDefault();
                        //    alert($('#cari').val("TEST"));
                            // parts_sales_order_datatable.draw();
                            dataTable.draw();
                            // alert("TEST");
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
    

    