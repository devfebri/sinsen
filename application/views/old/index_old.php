<body onload="tampil_grafik()">
<!--body  onload="JavaScript:timedRefresh(10000);"-->
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
    if($_SESSION["setting"] == 'none') {                    
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
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">          
            <h3>
              <?php 
              $motor = $this->db->query("SELECT COUNT(*) as jum FROM tr_scan_barcode WHERE tipe = 'RFS' AND status = 1")->row();
              echo $motor->jum;
              ?>
            </h3>
            <p>Stock Ready</p>
          </div>
          <div class="icon">
            <i class="fa fa-motorcycle"></i>
          </div>          
          <?php  
          $group = $this->session->userdata('group');
          if($group == '1'){          
          ?>
          <a href="h1/monitor_history" class="small-box-footer">See more <i class="fa fa-arrow-circle-right"></i></a>          
          <?php } ?>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>
              <?php 
              $dealer = $this->db->query("SELECT COUNT(*) as jum FROM ms_dealer WHERE active = 1")->row();
              echo $dealer->jum;
              ?>              
            </h3>
            <p>Dealers</p>
          </div>
          <div class="icon">
            <i class="fa fa-home"></i>
          </div>         
          <?php  
          $group = $this->session->userdata('group');
          if($group == '1'){          
          ?>          
          <a href="master/dealer" class="small-box-footer">See more <i class="fa fa-arrow-circle-right"></i></a>          
          <?php } ?>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>
              <?php 
              $kary = $this->db->query("SELECT COUNT(*) as jum FROM ms_karyawan WHERE active = 1")->row();
              echo $kary->jum;
              ?>
            </h3>
            <p>Employees</p>
          </div>
          <div class="icon">
            <i class="fa fa-group"></i>            
          </div>
          <?php  
          $group = $this->session->userdata('group');
          if($group == '1'){          
          ?>          
          <a href="master/karyawan" class="small-box-footer">See more <i class="fa fa-arrow-circle-right"></i></a>          
          <?php } ?>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">         
          <div class="inner">
            <h3>
              <?php 
              $y = date("Y");
              $sales = $this->db->query("SELECT COUNT(*) as jum FROM tr_sales_order WHERE LEFT(tgl_cetak_invoice,4) = '$y'")->row();
              echo $sales->jum;
              ?>
            </h3>
            <p>Sales on <?php echo $y ?></p>
          </div>
          <div class="icon">
            <i class="fa fa-tag"></i>
          </div>          
          <?php  
          $group = $this->session->userdata('group');
          if($group == '1'){          
          ?>          
          <a href="h1/real_stok_dealer" class="small-box-footer">See more <i class="fa fa-arrow-circle-right"></i></a>          
          <?php } ?>
        </div>
      </div><!-- ./col -->
    </div><!-- /.row -->
    <div class="row">
      <section class="col-lg-12 connectedSortable">
        <div class="box box-success">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Infografis Penjualan</h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <div id="container" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>
        </div>
      </section>
      <section class="col-lg-12 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Realtime Stock MD</h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <div id="container2" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>
        </div>
      </section>
      <section class="col-lg-6 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">20 Motor Terlaris</h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <div id="container3" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>
        </div>
      </section>
      <section class="col-lg-6 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">20 Dealer Teraktif</h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <div id="container4" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>
        </div>
      </section>
      <div class="col-md-6">
        <!-- USERS LIST -->
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Online Users</h3>

            <div class="box-tools pull-right">
              <!-- <span class="label label-danger">8 New Members</span> -->
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body no-padding">
            <ul class="users-list clearfix">
              <?php 
              $sql = $this->db->query("SELECT * FROM ms_user WHERE status = 'online' ORDER BY last_login_date DESC LIMIT 0,8");
              foreach ($sql->result() as $row) {                
              ?>
              <li>
                <?php                
                if($row->avatar != ""){
                  echo "<img src='assets/panel/images/user/$row->avatar' class='user-image'  style='width:50%;' alt='User Image'>";                
                }else{
                  echo "<img src='assets/panel/images/user/admin-lk.jpg' class='user-image' style='width:50%;' alt='User Image'>";                                
                } 
                ?>                
                <a class="users-list-name" href="master/user/view?id=<?php echo $row->id_user ?>"><?php echo $row->username; ?></a>                
                <span class="users-list-date"><?php echo $row->last_login_date ?></span>
              </li>              
              <?php } ?>
            </ul>
            <!-- /.users-list -->
          </div>
          <!-- /.box-body -->
          <?php  
          $group = $this->session->userdata('group');
          if($group == '1'){          
          ?>          
          <div class="box-footer text-center">
            <a href="master/user" class="uppercase">View All Users</a>
          </div>
          <?php } ?>
          <!-- /.box-footer -->
        </div>
        <!--/.box -->
      </div>      
    </div>    

  </div>
</div>

<script type="text/javascript">
function timedRefresh(timeoutPeriod) {
    setTimeout("location.reload(true);",timeoutPeriod);
}
</script>
<script src="assets/panel/dist/js/canvas.min.js"></script>
<script src="assets/panel/js_chart/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="assets/panel/js_chart/highcharts.js" type="text/javascript"></script>
<script src="assets/panel/js_chart/exporting.js" type="text/javascript"></script>
<script>
function tampil_grafik(){
  pie2();
}
function pie2(){

var chart = new CanvasJS.Chart("container2", {
  theme: "light2", // "light1", "light2", "dark1", "dark2"
  exportEnabled: true,
  animationEnabled: true,
  // title: {
  //   text: "",
  //   FontSize: 10
  // },
  data: [{
    type: "pie",
    startAngle: 25,    
    toolTipContent: "<b>{label}</b>: {y}%",
    showInLegend: "true",
    legendText: "{label}",
    indexLabelFontSize: 12,
    indexLabel: "{label} - {y}%",
    dataPoints: [
    <?php 
    $sql = $this->db->query("SELECT tipe_motor,COUNT(no_mesin) as jum,COUNT(no_mesin)/(SELECT COUNT(no_mesin) FROM tr_scan_barcode)*100 AS tot FROM tr_scan_barcode WHERE status = '1' GROUP BY tipe_motor ORDER BY tipe_motor ASC");
    foreach ($sql->result() as $isi) {
      //echo "{ y: 51.08, label: 'Chrome' },";
      $r = round($isi->tot,2);
      echo "{ y : $r, label: '$isi->tipe_motor ($isi->jum)'},";        
    }
    ?>
    ]
  }]
});
chart.render();

}
</script>
<script type="text/javascript">
    var chart1; // globally available
$(document).ready(function() {
      chart1 = new Highcharts.Chart({
         chart: {
            renderTo: 'container',
            type: 'column'
         },   
         title: {
            text: 'Jumlah Penjualan Dealer Per <?php echo date("Y") ?>'
         },
         xAxis: {
            categories: ['Dealer']
         },
         yAxis: {
            title: {
               text: ''
            }
         },
              series:             
            [
    <?php     
    $th = date("Y");
    $sql   = "SELECT ms_dealer.nama_dealer,ms_dealer.kode_dealer_md, COUNT(no_mesin) AS jum FROM tr_sales_order INNER JOIN ms_dealer ON tr_sales_order.id_dealer=ms_dealer.id_dealer
              WHERE tr_sales_order.status_so = 'so_invoice' AND LEFT(tr_sales_order.tgl_cetak_invoice,4) = '$th'
              GROUP BY tr_sales_order.id_dealer
              ORDER BY ms_dealer.nama_dealer ASC";
    $cek = $this->db->query($sql);
    foreach ($cek->result() as $r) {    
        $dealer=$r->kode_dealer_md." ".$r->nama_dealer;
        $jumlah=$r->jum;                    
        ?>
        {
          name: '<?php echo $dealer; ?>',
          data: [<?php echo $jumlah; ?>]
        },
        <?php } ?>
]
});
}); 
</script>
<script type="text/javascript">
    var chart1; // globally available
$(document).ready(function() {
      chart1 = new Highcharts.Chart({
         chart: {
            renderTo: 'container3',
            type: 'column'
         },   
         title: {
         <?php 
         $y = date("Y-m-d");
         $tanggal = date("F Y", strtotime($y));
         ?>
            text: '<?php echo $tanggal ?>'
         },
         xAxis: {
            categories: ['Tipe']
         },
         yAxis: {
            title: {
               text: ''
            }
         },
              series:             
            [
    <?php     
    $th = date("Y-m");
    $sql   = "SELECT ms_tipe_kendaraan.tipe_ahm,COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
          INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin=tr_scan_barcode.no_mesin  
          INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
          WHERE tr_sales_order.status_so = 'so_invoice' AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th'
          GROUP BY tr_scan_barcode.tipe_motor ORDER BY ms_tipe_kendaraan.tipe_ahm ASC LIMIT 0,20";    
    $cek = $this->db->query($sql);
    foreach ($cek->result() as $r) {    
        $jual=$r->tipe_ahm;
        $jumlah=$r->jum;                    
        ?>
        {
          name: '<?php echo $jual; ?>',
          data: [<?php echo $jumlah; ?>]
        },
        <?php } ?>
]
});
}); 
</script>
<script type="text/javascript">
    var chart1; // globally available
$(document).ready(function() {
      chart1 = new Highcharts.Chart({
         chart: {
            renderTo: 'container4',
            type: 'column'
         },   
         title: {
         <?php 
         $y = date("Y-m-d");
         $tanggal = date("F Y", strtotime($y));
         ?>
            text: 'Aktifitas Dealer <?php echo $tanggal ?>'
         },
         xAxis: {
            categories: ['Nama Dealer']
         },
         yAxis: {
            title: {
               text: ''
            }
         },
              series:             
            [
    <?php     
    $th = date("Y-m");
    $sql   = "SELECT ms_dealer.nama_dealer,COUNT(tr_penerimaan_unit_dealer_detail.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
            INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
            INNER JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
            GROUP BY ms_dealer.nama_dealer ORDER BY jum DESC LIMIT 0,20";    
    $cek = $this->db->query($sql);
    foreach ($cek->result() as $r) {    
        $dealer=$r->nama_dealer;
        $jumlah=$r->jum;                    
        ?>
        {
          name: '<?php echo $dealer; ?>',
          data: [<?php echo $jumlah; ?>]
        },
        <?php } ?>
]
});
}); 
</script>