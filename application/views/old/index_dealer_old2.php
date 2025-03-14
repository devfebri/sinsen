<body onload="tampil_grafik();daily();">
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
              $id_dealer = $this->m_admin->cari_dealer(); 
              $tgl = date("Y-m-d");
              $ssu = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual FROM tr_sales_order 
                INNER JOIN tr_ssu_detail ON tr_sales_order.no_mesin = tr_ssu_detail.no_mesin
                INNER JOIN tr_ssu ON tr_ssu_detail.id_ssu = tr_ssu.id_ssu                                          
                WHERE tr_ssu.start_date = '$tgl' AND tr_sales_order.id_dealer = '$id_dealer'")->row();              
              echo $ssu->total_jual;
              ?>
            </h3>
            <p>SSU Hari ini</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar"></i>
          </div>                              
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>
              <?php 
              $id_dealer = $this->m_admin->cari_dealer();              
              $tgl = date("Y-m-d");
              $tgl2 = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));
              $ssu = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual FROM tr_sales_order 
                INNER JOIN tr_ssu_detail ON tr_sales_order.no_mesin = tr_ssu_detail.no_mesin
                INNER JOIN tr_ssu ON tr_ssu_detail.id_ssu = tr_ssu.id_ssu                                          
                WHERE tr_ssu.start_date = '$tgl2' AND tr_sales_order.id_dealer = '$id_dealer'")->row();              
              echo $ssu->total_jual;
              ?>              
            </h3>
            <p>SSU H-1</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar"></i>
          </div>                             
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>
              <?php 
              $id_dealer = $this->m_admin->cari_dealer();
              $kary = $this->db->query("SELECT COUNT(*) as jum FROM ms_karyawan_dealer WHERE id_dealer = '$id_dealer' AND active = 1")->row();
              echo $kary->jum;
              ?>
            </h3>
            <p>Cummulative</p>
          </div>
          <div class="icon">
            <i class="fa fa-times"></i>            
          </div>                    
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">         
          <div class="inner">
            <h3>
              <?php 
              $y = date("Y");
              $id_dealer = $this->m_admin->cari_dealer();
              $motor = $this->db->query("SELECT COUNT(*) as jum FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer
                  ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer=tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer
                  INNER JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin 
                  WHERE tr_penerimaan_unit_dealer.status = 'close' AND tr_scan_barcode.tipe = 'RFS' 
                  AND tr_scan_barcode.status = 4 AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'")->row();              
              echo $motor->jum;
              ?>
            </h3>
            <p>Real Time</p>
          </div>
          <div class="icon">
            <i class="fa fa-motorcycle"></i>
          </div>                              
        </div>
      </div><!-- ./col -->
    </div><!-- /.row -->
    <div class="row">
       <section class="col-lg-6 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Finance Company Contribution</h3>
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


      <section class="col-lg-6 connectedSortable">
        <div class="box box-success">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Sales Performance</h3>            
            <button class="btn btn-danger btn-flat btn-sm" onclick="change_daily()">Daily</button>            
            <button class="btn bg-primary btn-flat btn-sm" onclick="change_monthly()">Monthly</button>            
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box_1">
            <div id="container" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>
          <div class="box-body chat" id="chat-box_2">
            <div id="container_1" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>
        </div>
      </section>

      <section class="col-lg-6 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Sales Comparison</h3>
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
            <h3 class="box-title">Sales Structure By DP</h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <table class="table table-bordered table-hovered" id="example7">
              <thead>
                <tr>
                  <th rowspan="2">Finance Company</th>
                  <th colspan="3">Contribution By DP</th>                
                  <th rowspan="2">Total</th>
                </tr>
                <tr>
                  <th>10%</th>  
                  <th>10% - 20%</th>  
                  <th>20%</th>  
                </tr>
              </thead>
              <tbody>
              
            </table>
          </div>
        </div>
      </section>

      <section class="col-lg-6 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Finance Company Account Receivable</h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <div id="container6" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>
        </div>
      </section>

      <section class="col-lg-6 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Stock Monitoring</h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
             <table class="table table-hovered table-bordered" id="example2">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Tipe</th>
                  <th>Warna</th>
                  <th>Stock</th>
                  <th>Unfill</th>
                  <th>Intransit</th>                  
                </tr>
              </thead>
              <tbody>
                <?php 
                $dt_list = $this->db->query("SELECT ms_item.id_item,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM ms_item
                  LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                  LEFT JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna                                
                  ORDER BY ms_item.id_item ASC");     

                $no=1;
                $t_qty=0;$t_unfill=0;$t_in=0;
                foreach($dt_list->result() as $row) {   
                  $id_dealer = $this->m_admin->cari_dealer();            
                  $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                      LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                      LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                      LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                      LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                      LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                      WHERE tr_scan_barcode.id_item = '$row->id_item' AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
                      AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close'")->row();    
                  $cek_unfill = $this->db->query("SELECT COUNT(tr_do_po_detail.id_item) AS jum FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
                                  INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                                  WHERE tr_picking_list.no_picking_list NOT IN (SELECT tr_surat_jalan.no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) AND
                                  tr_do_po_detail.id_item = '$row->id_item' AND tr_do_po.id_dealer = '$id_dealer' AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved'")->row();                        
                  $cek_unfill2 = $this->db->query("SELECT COUNT(tr_do_po_detail.id_item) AS jum FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                              WHERE tr_do_po.no_do NOT IN (SELECT no_do FROM tr_picking_list WHERE no_do IS NOT NULL) 
                              AND tr_do_po_detail.id_item = '$row->id_item' AND tr_do_po.id_dealer = '$id_dealer'")->row();
                  $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                              WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
                              AND tr_surat_jalan_detail.id_item = '$row->id_item' AND tr_surat_jalan.id_dealer = '$id_dealer'")->row();
                  if($cek_qty->jum > 0 or $cek_unfill->jum > 0 OR $cek_in->jum > 0){
                    echo "
                    <tr>                     
                      <td>$row->id_item</td>              
                      <td>$row->tipe_ahm</td>
                      <td>$row->warna</td>
                      <td>$cek_qty->jum</td>
                      <td>$cek_unfill->jum</td>
                      <td>$cek_in->jum</td>              
                    </tr>
                    ";
                    $t_qty = $t_qty + $cek_qty->jum;
                    $t_unfill = $t_unfill + $cek_unfill->jum;
                    $t_in = $t_in + $cek_in->jum;
                  }
                }
                ?>
              </tbody>              
            </table>
          </div>
        </div>
      </section>

      <section class="col-lg-12 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Sales People Performance</h3>
            <button class="btn btn-danger btn-flat btn-sm" onclick="change_district()">District</button>            
            <button class="btn bg-primary btn-flat btn-sm" onclick="change_region()">Region</button>            
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box_7a">
            <div id="container7a" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>
          <div class="box-body chat" id="chat-box_7b">
            <div id="container7b" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>
        </div>
      </section>
            
    </div>    

  </div>
</div>

<script type="text/javascript">
function timedRefresh(timeoutPeriod) {
    setTimeout("location.reload(true);",timeoutPeriod);
}
</script>
<script src="assets/panel/dist/js/canvas.min.js"></script>


<script src="assets/panel/plugins/graphic_new/highcharts.js"></script>
<script src="assets/panel/plugins/graphic_new/exporting.js"></script>
<script src="assets/panel/plugins/graphic_new/export-data.js"></script>
<script src="assets/panel/plugins/graphic_new/series-label.js"></script>

<script>
function daily(){
  $("#chat-box_1").show();
  $("#chat-box_2").hide();

  $("#chat-box_7a").show();
  $("#chat-box_7b").hide();
}
function change_daily(){
  $("#chat-box_2").show();
  $("#chat-box_1").hide(); 
}
function change_monthly(){
  $("#chat-box_1").show();
  $("#chat-box_2").hide(); 
}
function change_district(){
  $("#chat-box_7a").show();
  $("#chat-box_7b").hide(); 
}
function change_region(){
  $("#chat-box_7b").show();
  $("#chat-box_7a").hide(); 
}
function tampil_grafik(){
  pie2();
}
function pie2(){

var chart = new CanvasJS.Chart("container4", {
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
    $id_dealer = $this->m_admin->cari_dealer();
    $sql = $this->db->query("SELECT ms_finance_company.finance_company, COUNT(tr_sales_order.no_mesin) AS jum
      FROM tr_sales_order
      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
      INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
      WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'
      GROUP BY tr_spk.id_finance_company");
    foreach ($sql->result() as $isi) {
      //echo "{ y: 51.08, label: 'Chrome' },";
      $tt = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order
        INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
        INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
        WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'")->row();
      $y = ($isi->jum / $tt->jum) * 100;
      $r = round($y,2);
      echo "{ y : $r, label: '$isi->finance_company ($isi->jum unit)'},";        
    }
    ?>
    ]
  }]
});
chart.render();

}
</script>

<!-- Salec Comparison -->
<script type="text/javascript">

var chart1; // globally available
  $(document).ready(function() {
      chart1 = new Highcharts.chart('container2', {
    chart: {
        type: 'column'
    },
    title: {
       <?php 
       $y = date("Y-m-d");
       $tanggal = date("F Y", strtotime($y));
       ?>
        text: '<?php echo $tanggal ?>'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [
          <?php                    
          $id_dealer = $this->m_admin->cari_dealer();           
          $sq = $this->db->query("SELECT DISTINCT(ms_segment.segment) AS segment,ms_segment.id_segment FROM tr_spk 
              INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
              INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
              WHERE tr_sales_order.id_dealer = '$id_dealer'
              ORDER BY ms_segment.segment ASC");
          foreach ($sq->result() as $isi) {

            $bln = date("m");
            $bln_1 = date("m") - 1;    
            if($bln_1 == "0"){
              $bln_1_fix = "12";
              $ty   = date("Y")-1;
              $th_1 = $ty."-".$bln_1_fix;
            }else{
              $bln_1_fix = $bln_1;
              $ty   = date("Y");
              $th_1 = $ty."-".$bln_1_fix;
            }    
            $th     = date("Y-m");
            $th_b   = date("F Y", strtotime($th));
            $th_1b   = date("F Y", strtotime($th_1));

            //cek bulan lalu
            $cek_1 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th_1' AND ms_segment.id_segment = '$isi->id_segment'");
            if($cek_1->num_rows() > 0){
              $t = $cek_1->row();
              $hasil_1 = $t->total_jual;
            }else{
              $hasil_1 = 0;
            }

            //cek bulan sekarang
            $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th' AND ms_segment.id_segment = '$isi->id_segment'");
            if($cek->num_rows() > 0){
              $t = $cek->row();
              $hasil = $t->total_jual;
            }else{
              $hasil = 0;
            }
            
            //cari x
            if($hasil_1 != 0){
              $x_persen = round((($hasil - $hasil_1) / $hasil_1),2);
            }else{
              $x_persen = round(($hasil - $hasil_1),2);
            }          

           echo "'$isi->segment ($x_persen %)'".",";
         }
         ?>        
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Unit'
        }
    },   
    series: [{
        name: '<?php echo $th_1b ?>',
        data: [
        <?php 
        $sq = $this->db->query("SELECT DISTINCT(ms_segment.segment) AS segment,ms_segment.id_segment FROM tr_spk 
              INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
              INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
              WHERE tr_sales_order.id_dealer = '$id_dealer'
              ORDER BY ms_segment.segment ASC");
        foreach ($sq->result() as $isi) {
          $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual_1                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th_1' AND ms_segment.id_segment = '$isi->id_segment'");          
          if($cek->num_rows() > 0){
            $t = $cek->row();
            $hasil = $t->total_jual_1;
          }else{
            $hasil = 0;
          }
          echo $hasil.",";          
        }
        ?>      
        ]

    }, {
        name: '<?php echo $th_b ?>',
        data: [
        <?php 
        $sq = $this->db->query("SELECT DISTINCT(ms_segment.segment) AS segment,ms_segment.id_segment FROM tr_spk 
              INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
              INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
              WHERE tr_sales_order.id_dealer = '$id_dealer'
              ORDER BY ms_segment.segment ASC");
        foreach ($sq->result() as $isi) {
          $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.id_dealer = '$id_dealer'
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th' AND ms_segment.id_segment = '$isi->id_segment'");
          if($cek->num_rows() > 0){
            $t = $cek->row();
            $hasil = $t->total_jual;
          }else{
            $hasil = 0;
          }
          echo $hasil.",";          
        }
        ?>
        ]

    }]
});
});
</script>
<!-- Sales People Performance District-->
<script type="text/javascript">

var chart1; // globally available
  $(document).ready(function() {
      chart1 = new Highcharts.chart('container7a', {
    chart: {
        type: 'column'
    },
    title: {
       <?php 
       $y = date("Y-m-d");
       $tanggal = date("F Y", strtotime($y));
       $id_dealer=$this->m_admin->cari_dealer();
       $cari_daerah = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
      INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
      INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
      INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
      WHERE ms_dealer.id_dealer='$id_dealer'")->row();
       ?>
        text: '<?php echo $cari_daerah->kabupaten; ?>'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: ['Total Penjualan'],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Unit'
        }
    },    
    series:             
            [
    <?php     
    $bln = date("Y-m");
    $id_dealer=$this->m_admin->cari_dealer();
    $cari_daerah = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
      INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
      INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
      INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
      WHERE ms_dealer.id_dealer='$id_dealer'")->row();
    $sq = $this->db->query("SELECT DISTINCT(tr_prospek.id_karyawan_dealer) AS sales,ms_karyawan_dealer.nama_lengkap,tr_spk.no_spk FROM tr_sales_order 
        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer
        LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
        INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
        INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
        INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'              
        AND ms_kabupaten.id_kabupaten = '$cari_daerah->id_kabupaten'
        ORDER BY ms_karyawan_dealer.nama_lengkap ASC");
    foreach ($sq->result() as $isi) {
      $sr = $this->db->query("SELECT count(tr_sales_order.no_mesin) as jum FROM tr_sales_order 
        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'
        AND tr_prospek.id_karyawan_dealer = '$isi->sales'")->row();     
        $jumlah=$sr->jum;
        $nama=$isi->nama_lengkap;                    
        ?>
        {
          name: '<?php echo $nama; ?>',
          data: [<?php echo $jumlah; ?>]
        },
        <?php } ?>
    ]    
});
});
</script>

<!-- Sales People Performance Region-->
<script type="text/javascript">

var chart1; // globally available
  $(document).ready(function() {
      chart1 = new Highcharts.chart('container7b', {
    chart: {
        type: 'column'
    },
    title: {
       <?php 
       $y = date("Y-m-d");
       $tanggal = date("F Y", strtotime($y));
       $id_dealer=$this->m_admin->cari_dealer();
       $cari_daerah = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
      INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
      INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
      INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
      WHERE ms_dealer.id_dealer='$id_dealer'")->row();
       ?>
        text: '<?php echo $cari_daerah->provinsi; ?>'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: ['Total Penjualan'],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Unit'
        }
    },    
    series:             
            [
    <?php     
    $bln = date("Y-m");
    $id_dealer=$this->m_admin->cari_dealer();
    $cari_daerah = $this->db->query("SELECT * FROM ms_dealer INNER JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
      INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
      INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
      INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
      WHERE ms_dealer.id_dealer='$id_dealer'")->row();
    $sq = $this->db->query("SELECT DISTINCT(tr_prospek.id_karyawan_dealer) AS sales,ms_karyawan_dealer.nama_lengkap,tr_spk.no_spk FROM tr_sales_order 
        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        LEFT JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer
        LEFT JOIN ms_kelurahan ON ms_dealer.id_kelurahan = ms_kelurahan.id_kelurahan
        INNER JOIN ms_kecamatan ON ms_kelurahan.id_kecamatan = ms_kecamatan.id_kecamatan
        INNER JOIN ms_kabupaten ON ms_kecamatan.id_kabupaten = ms_kabupaten.id_kabupaten
        INNER JOIN ms_provinsi ON ms_kabupaten.id_provinsi = ms_provinsi.id_provinsi
        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'              
        AND ms_provinsi.id_provinsi = '$cari_daerah->id_provinsi'
        ORDER BY ms_karyawan_dealer.nama_lengkap ASC");
    foreach ($sq->result() as $isi) {
      $sr = $this->db->query("SELECT count(tr_sales_order.no_mesin) as jum FROM tr_sales_order 
        LEFT JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
        LEFT JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
        LEFT JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
        WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'
        AND tr_prospek.id_karyawan_dealer = '$isi->sales'")->row();     
        $jumlah=$sr->jum;
        $nama=$isi->nama_lengkap;                    
        ?>
        {
          name: '<?php echo $nama; ?>',
          data: [<?php echo $jumlah; ?>]
        },
        <?php } ?>
    ]    
});
});
</script>

<!-- Line Graphic Sales Performance Monthly -->
<script type="text/javascript">

var chart1; // globally available
  $(document).ready(function() {
      chart1 = new Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        <?php 
       $y = date("Y-m");
       $tanggal = date("F Y", strtotime($y));
       ?>
        text: '<?php echo $tanggal ?>'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Total Penjualan (unit)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Sales Order',
        data: [
        <?php 
        $id_dealer = $this->m_admin->cari_dealer();
        for ($i=1; $i <= 12 ; $i++) { 
          $y = date("Y");
          $b  = sprintf("%'.02d",$i);    
          $bln = $y."-".$b;
          $sql = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_sales_order WHERE tr_sales_order.tgl_cetak_invoice 
              IS NOT NULL AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bln'
              AND tr_sales_order.id_dealer = '$id_dealer'");
          foreach ($sql->result() as $isi) {
            echo $isi->jum.",";
          }
        }
        ?>
        ]
    }]
});
});  
</script>

<!-- Line Graphic Sales Performance Daily -->
<script type="text/javascript">

var chart1; // globally available
  $(document).ready(function() {
      chart1 = new Highcharts.chart('container_1', {
    chart: {
        type: 'line'
    },
    title: {
        <?php 
       $y = date("Y-m-d");
       $tanggal = date("d F Y", strtotime($y));
       ?>
        text: '<?php echo $tanggal ?>'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [
        <?php 
        $tgl_akhir_bulan=date('t');
        for ($i=1; $i <= $tgl_akhir_bulan; $i++) { 
          echo "'$i'".",";
        }
        ?>
        ]
    },
    yAxis: {
        title: {
            text: 'Total Penjualan (unit)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Sales Order',
        data: [
        <?php 
        $id_dealer = $this->m_admin->cari_dealer();
        for ($i=1; $i <= 30 ; $i++) { 
          $y = date("Y-m");
          $b  = sprintf("%'.02d",$i);    
          $bln = $y."-".$b;
          $sql = $this->db->query("SELECT count(no_mesin) AS jum FROM tr_sales_order WHERE tr_sales_order.tgl_cetak_invoice 
              IS NOT NULL AND tr_sales_order.tgl_cetak_invoice = '$bln'
              AND tr_sales_order.id_dealer = '$id_dealer'");
          foreach ($sql->result() as $isi) {
            echo $isi->jum.",";
          }
        }
        ?>
        ]
    }]
});
});  
</script>

<script type="text/javascript">

var chart1; // globally available
  $(document).ready(function() {
      chart1 = new Highcharts.chart('container6', {
    chart: {
        type: 'column'
    },
    title: {
       <?php 
       $y = date("Y-m");
       $tanggal = date("F Y", strtotime($y));
       ?>
        text: '<?php echo $tanggal ?>'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [
          <?php                               
          $bln = date("Y-m-d");$id_dealer=$this->m_admin->cari_dealer();          
          $sql = $this->db->query("SELECT ms_finance_company.finance_company, COUNT(tr_sales_order.no_mesin) AS jum
              FROM tr_sales_order
              INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
              INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
              WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'
              GROUP BY tr_spk.id_finance_company");
          foreach ($sql->result() as $isi) {                                    
           echo "'$isi->finance_company'".",";
         }
         ?>        
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Unit'
        }
    },    
    series:             
            [
    <?php     
    $bln = date("Y-m-d");$id_dealer=$this->m_admin->cari_dealer();
    $sql = $this->db->query("SELECT ms_finance_company.finance_company, ms_finance_company.id_finance_company, COUNT(tr_sales_order.no_mesin) AS jum
              FROM tr_sales_order
              INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk 
              INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
              WHERE tr_sales_order.id_dealer = '$id_dealer' AND tr_spk.jenis_beli = 'Kredit'
              GROUP BY tr_spk.id_finance_company");
    foreach ($sql->result() as $isi) {      
        $jumlah=$isi->jum;
        $nama=$isi->finance_company;                    
        ?>
        {
          name: '<?php echo $nama; ?>',
          data: [<?php echo $jumlah; ?>]
        },
        <?php } ?>
    ]    
});
});
</script>