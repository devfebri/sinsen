<body>
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
              // $tgl = date("Y-m-d");
              $tgl = gmdate("Y-m-d", time()+60*60*7);          
              $jum = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM ms_dealer INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  WHERE tr_sales_order.tgl_cetak_invoice = '$tgl' 
                  LIMIT 0,10")->row();              

              $jum2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
                    INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
                    INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
                    WHERE tr_sales_order_gc.tgl_cetak_invoice = '$tgl'")->row();
              echo $jum->jum + $jum2->jum;
              ?>
            </h3>
            <p>Penjualan Hari ini</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar"></i>
          </div>          
          <!-- <?php  
          $group = $this->session->userdata('group');
          if($group == '1'){          
          ?>
          <a href="h1/monitor_history" class="small-box-footer">See more <i class="fa fa-arrow-circle-right"></i></a>          
          <?php } ?> -->
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>
              <?php 
              // $tgl = date("Y-m-d");  
              $tgl = gmdate("Y-m-d", time()+60*60*7);                      
              $tgl_up     = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));
              $jum = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM ms_dealer INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  WHERE tr_sales_order.tgl_cetak_invoice = '$tgl_up' 
                  AND tr_sales_order.tgl_cetak_invoice IS NOT NULL AND tr_sales_order.tgl_cetak_invoice2 IS NOT NULL
                  LIMIT 0,10")->row();              
              $jum2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
                    INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
                    INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
                    WHERE tr_sales_order_gc.tgl_cetak_invoice = '$tgl_up'")->row();
              echo $jum->jum + $jum2->jum;              
              ?>              
            </h3>
            <p>Kemarin</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar"></i>
          </div>         
          <!-- <?php  
          $group = $this->session->userdata('group');
          if($group == '1'){          
          ?>          
          <a href="master/dealer" class="small-box-footer">See more <i class="fa fa-arrow-circle-right"></i></a>          
          <?php } ?> -->
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>
              <?php 
              $bulan = date("Y-m");                            
              $jum = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM ms_dealer INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan' 
                  LIMIT 0,10")->row();  
              $jum2 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
                    INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
                    INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
                    WHERE LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan'")->row()->jum;            
              $tot_bulan_ini = $jum->jum+$jum2;
              echo $tot_bulan_ini;
              ?>
            </h3>
            <p>Total Bulan Ini</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar"></i>            
          </div>
          <!-- <?php  
          $group = $this->session->userdata('group');
          if($group == '1'){          
          ?>          
          <a href="master/karyawan" class="small-box-footer">See more <i class="fa fa-arrow-circle-right"></i></a>          
          <?php } ?> -->
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">         
          <div class="inner">
            <p>
              <?php 
              // $y = date("d F Y");    
              $y = gmdate("d F Y", time()+60*60*7);          
              $f = gmdate("H:i", time()+60*60*7);          
              // $f = date("h:i");
              echo $y."<br>".$f;

              ?>
            </p>
            <p>Terakhir di-Update</p>
          </div>
          <div class="icon">
            <i class="fa fa-tag"></i>
          </div>          
          <!-- <?php  
          $group = $this->session->userdata('group');
          if($group == '1'){          
          ?>          
          <a href="h1/real_stok_dealer" class="small-box-footer">See more <i class="fa fa-arrow-circle-right"></i></a>          
          <?php } ?> -->
        </div>
      </div><!-- ./col -->
    </div><!-- /.row -->
    <div class="row">
           
      

      <section class="col-lg-12 connectedSortable pull-left">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Tabel Stok</b></h3>            
          </div>
          <div class="box-body chat" id="chat-box">
            <table class="table table-bordered table-hovered table-striped " id="table_ajax">
              <thead>
                <tr>
                  <th>Tipe</th>
                  <th>Deskripsi</th>
                  <th>Unfill AHM</th>
                  <th>Int AHM</th>
                  <th>Stok MD</th>
                  <th>Unfill Dealer</th>
                  <th>Int Dealer</th>
                  <th>Stok Dealer</th>
                  <th>Total Stok</th>
                  <th>Stok Market</th>
                  <th>Sales Dealer</th>
                  <th>Stock Days MD</th>
                </tr>
              </thead>
              <tbody id="showDetailStok">                              
              </tbody>
              <tfoot>
                <tr  bgcolor="yellow">
                  <th colspan="2"></th>              
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
                <?php
                $sql = $this->db->query("SELECT ms_tipe_kendaraan.id_tipe_kendaraan,ms_tipe_kendaraan.tipe_ahm,(SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan AND status = '1' AND tipe='RFS') AS ready FROM ms_tipe_kendaraan");                 
                $a1=0;$a2=0;$a3=0;$a4=0;$a5=0;$a6=0;$a7=0;$a8=0;$a9=0;$a10=0;
                foreach ($sql->result() as $row) {
                  $cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND status = '2'")->row();
                  $cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND status = '3'")->row();
                  $cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'NRFS' AND status < 4")->row();
                  $cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND tipe = 'PINJAMAN' AND status < 4")->row();
                  
                
                  
                  $cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb WHERE tr_sipb.id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row();                
                  $cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list WHERE tr_shipping_list.id_modell = '$row->id_tipe_kendaraan'")->row();          
                  $sipb = 0;
                  $total = $row->ready + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum  + $cek_pinjaman->jum;          
                  if($cek_in1->jum - $cek_in2->jum > 0){
                    $rr = $cek_in1->jum - $cek_in2->jum;
                  }else{
                    $rr = 0;
                  }
         
                  $cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list WHERE tr_shipping_list.id_modell = '$row->id_tipe_kendaraan'")->row(); 
                  $cek_sl2_1 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode
                    LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
                    WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row();      
                  $cek_sl1_jum = $cek_sl1->jum;
                  $cek_sl2_jum = $cek_sl2_1->jum;      
                  if($cek_sl1_jum - $cek_sl2_jum >= 0){            
                    $r2 = $cek_sl1_jum - $cek_sl2_jum;     
                  }else{
                    $r2 = 0;
                  }        
                  
                  $stok_md = $total;

                  $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                    LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                    LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                    LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                    LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                    LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                    WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan' AND tr_scan_barcode.status = '4'")->row();                     
                  
                  $cek_unfill2 = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po 
                    LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                    LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
                    INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
                    WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)")->row();
                  
                  $cek_unfill = $this->db->query("SELECT COUNT(tr_picking_list_view.no_mesin) AS jum FROM tr_picking_list_view 
                    INNER JOIN ms_item ON tr_picking_list_view.id_item = ms_item.id_item 
                    WHERE tr_picking_list_view.no_mesin NOT IN 
                      (SELECT no_mesin FROM tr_surat_jalan_detail 
                        INNER JOIN tr_surat_jalan ON tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan 
                        WHERE tr_surat_jalan.no_picking_list IS NOT NULL)
                    AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row();
                  
                  if(isset($cek_unfill->jum)){
                    $unfill = $cek_unfill->jum;
                  }else{
                    $unfill = 0;
                  }
                  
                  $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                    INNER JOIN ms_item ON tr_surat_jalan_detail.id_item = ms_item.id_item
                    WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)                    
                    AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row();        
                  $total_stock = $r2 + $stok_md + $cek_unfill->jum + $cek_in->jum + $cek_qty->jum;;
                  $stock_market = $stok_md + $cek_unfill->jum + $cek_in->jum + $cek_qty->jum;

                  $cek_sales = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                    WHERE tr_scan_barcode.tipe_motor = '$row->id_tipe_kendaraan'")->row();
          
                  if($cek_sales->jum != 0){
                    $stock_days = ceil(($stok_md / $cek_sales->jum) * 30);
                  }else{
                    $stock_days = ceil(($stok_md) * 30);
                  } 
                  
                                    
                  $a1 += $rr;
                  $a2 += $r2;
                  $a3 += $stok_md;
                  $a4 += $unfill;
                  $a5 += $cek_in->jum;
                  $a6 += $cek_qty->jum;
                  $a7 += $total_stock;
                  $a8 += $stock_market;
                  $a9 += $cek_sales->jum;
                  $a10 += $stock_days;   
                }           

                ?>
                <tr bgcolor="#00AFEF">
                  <th colspan="2">Grand Total</th>              
                  <th><?php echo $a1 ?></th>
                  <th><?php echo $a2 ?></th>
                  <th><?php echo $a3 ?></th>
                  <th><?php echo $a4 ?></th>
                  <th><?php echo $a5 ?></th>
                  <th><?php echo $a6 ?></th>
                  <th><?php echo $a7 ?></th>
                  <th><?php echo $a8 ?></th>
                  <th><?php echo $a9 ?></th>
                  <th><?php echo $a10 ?></th>
                </tr>
              </tfoot> 
            </table>
          </div>
        </div>
      </section>      



      <section class="col-lg-6 connectedSortable pull-left">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Rank Dealer - <b><?php echo date("F Y") ?></b></h3>            
          </div>
          <div class="box-body chat" id="chat-box">
            <table class="table table-bordered table-hovered table-striped" id="tabel_finance">
              <thead>
                <tr>
                  <th>Rank</th>
                  <th>Kode Dealer</th>
                  <th>Dealer</th>
                  <th>Penjualan</th>
                  <th>Kontribusi</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $no=1;$tot=0;$kon2=0;
                $bulan = date("Y-m");
                $dealer = $this->db->query("SELECT ms_dealer.*,COUNT(tr_sales_order.no_mesin) AS jum 
                  FROM ms_dealer 
                  INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan' 
                  GROUP BY tr_sales_order.id_dealer ORDER BY jum DESC");

                $dealer2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM ms_dealer INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan'")->row();

                $jum_gc = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
                    INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
                    INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
                    WHERE LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan'")->row()->jum;

                foreach ($dealer->result() as $isi) {  
                  $jum_gc_d = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
                    INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
                    INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
                    WHERE LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan' AND id_dealer='$isi->id_dealer'")->row()->jum;                
                  $tot = $tot + ($isi->jum+$jum_gc_d);
                  $kon = round((($isi->jum / ($dealer2->jum+$jum_gc)) * 100),2);                  
                  $jum_ok = $isi->jum + $jum_gc_d;
                  echo "
                  <tr>
                    <td>$no</td>
                    <td>$isi->kode_dealer_md</td>
                    <td>$isi->nama_dealer</td>
                    <td>$jum_ok Unit</td>
                    <td>$kon</td>
                  </tr>
                  ";
                  $no++;
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3">Total</th>
                  <th><?php echo $tot ?> Unit</th>
                  <th></th>                  
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </section>
      
      <section class="col-lg-6 connectedSortable pull-right">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Sales Report SSU - <b><?php echo gmdate("d F Y H:i:s", time()+60*60*7); ?></b></h3>            
          </div>
          <div class="box-body chat" id="chat-box">
            <table class="table table-bordered table-hovered table-striped" id="tabel_finance2">
              <thead>
                <tr>
                  <th>Tipe</th>
                  <th>Penjualan Hari ini</th>
                  <th>Total Bulan ini</th>                
                </tr>
              </thead>
              <tbody>
                <?php 
                $no=1;$t=0;$b=0;
                // $tgl = date("Y-m-d");
                $tgl = gmdate("Y-m-d", time()+60*60*7);          
                $bulan = date("Y-m");
                $dealer = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,COUNT(tr_sales_order.no_mesin) AS jum,tr_scan_barcode.tipe_motor FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                  INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                  WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan'
                  GROUP BY tr_scan_barcode.tipe_motor ORDER BY jum DESC");
                $dealer3_b = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
                    INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
                    INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
                    WHERE LEFT(tr_sales_order_gc.tgl_cetak_invoice,7) = '$bulan'")->row();
                foreach ($dealer->result() as $isi) {
                  $dealer2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                    
                    WHERE tr_sales_order.tgl_cetak_invoice = '$tgl' AND tr_scan_barcode.tipe_motor = '$isi->tipe_motor'")->row();

                  $dealer3 = $this->db->query("SELECT COUNT(tr_sales_order_gc_nosin.no_mesin) AS jum FROM tr_sales_order_gc_nosin  
                    INNER JOIN tr_scan_barcode ON tr_sales_order_gc_nosin.no_mesin = tr_scan_barcode.no_mesin                    
                    INNER JOIN tr_sales_order_gc ON tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc
                    WHERE tr_sales_order_gc.tgl_cetak_invoice = '$tgl' AND tr_scan_barcode.tipe_motor = '$isi->tipe_motor'")->row();
                  $t = $t + $dealer2->jum + $dealer3->jum;
                  $b = $b + $isi->jum + $dealer3->jum;
                  echo "
                  <tr>
                    <td>$isi->tipe_ahm</td>                    
                    <td>$dealer2->jum Unit</td>
                    <td>$isi->jum Unit</td>
                  </tr>
                  ";
                  $no++;
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>Total</th>
                  <th><?php echo $t ?> Unit</th>
                  <th><?php echo $tot_bulan_ini ?> Unit</th>                  
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </section>
      
     
      <section class="col-lg-6 connectedSortable pull-left">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Sales Structure By DP</h3>            
          </div>
          <div class="box-body chat" id="chat-box">
            <table class="table table-bordered table-hovered table-striped" id="tabel_finance3">
              <thead>
                <tr>
                  <th rowspan="2">No</th>
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
              <?php 
              $no=1;$p1=0;$p2=0;$p3=0;$p4=0;
              $sql = $this->db->query("SELECT * FROM ms_finance_company WHERE active = 1");
              foreach ($sql->result() as $isi) {
                $spk = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
                    WHERE jenis_beli = 'Kredit'
                    AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
                $spk1 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
                    WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 0 AND 10
                    AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
                $spk2 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
                    WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 11 AND 20
                    AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
                $spk3 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
                    WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 > 20
                    AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();                
                if($spk->jum != 0){
                  $isi_spk1 = round((($spk1->jum / $spk->jum) * 100),2);                
                  $isi_spk2 = round((($spk2->jum / $spk->jum) * 100),2);                
                  $isi_spk3 = round((($spk3->jum / $spk->jum) * 100),2);                
                }else{
                  $isi_spk1 = round((($spk1->jum) * 100),2);                
                  $isi_spk2 = round((($spk2->jum) * 100),2);                
                  $isi_spk3 = round((($spk3->jum) * 100),2);                
                }
                $tot = $isi_spk1+$isi_spk2+$isi_spk3;
                $p1 = $p1+$isi_spk1;
                $p2 = $p2+$isi_spk2;
                $p3 = $p3+$isi_spk3;
                $p4 = $p4+$tot;
                if($tot!=0){
                  echo "
                  <tr>
                    <td>$no</td>
                    <td>$isi->finance_company</td>
                    <td>$isi_spk1 %</td>
                    <td>$isi_spk2 %</td>
                    <td>$isi_spk3 %</td>
                    <td>$tot %</td>
                  </tr>
                  ";
                  $no++;
                }
              }
              ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="2">Total</th>
                  <th><?php echo $p1 ?> %</th>
                  <th><?php echo $p2 ?> %</th>
                  <th><?php echo $p3 ?> %</th>
                  <th><?php echo $p4 ?> %</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </section>

      <section class="col-lg-6 connectedSortable pull-right">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Sales Structure By Finance Company</h3>            
          </div>
          <div class="box-body chat" id="chat-box">
            <div id="container4" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>          
        </div>
      </section> 

      <section class="col-lg-6 connectedSortable pull-left">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Sales By Segment</h3>            
          </div>
          <div class="box-body chat" id="chat-box">
            <div id="container3" style="min-width: 500px; height: 400px; margin: 0 auto"></div>
          </div>          
        </div>
      </section> 

    </div>    

  </div>
</div>

<!-- <script type="text/javascript">
function timedRefresh(timeoutPeriod) {
    setTimeout("location.reload(true);",timeoutPeriod);
}
</script>
 -->

<script src="assets/panel/plugins/graphic_new/highcharts.js"></script>
<script src="assets/panel/plugins/graphic_new/exporting.js"></script>
<script src="assets/panel/plugins/graphic_new/export-data.js"></script>
<script type="text/javascript" language="javascript" src="assets/panel/datatables/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="assets/panel/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript">

var chart1; // globally available
  $(document).ready(function() {
      chart1 = new Highcharts.chart('container4', {
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
          $sq = $this->db->query("SELECT DISTINCT(tr_spk.id_finance_company) AS finco,ms_finance_company.finance_company FROM tr_spk INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company ORDER BY ms_finance_company.finance_company ASC");
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
                INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th_1' AND tr_spk.id_finance_company = '$isi->finco'");
            if($cek_1->num_rows() > 0){
              $t = $cek_1->row();
              $hasil_1 = $t->total_jual;
            }else{
              $hasil_1 = 0;
            }

            //cek bulan sekarang
            $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th' AND tr_spk.id_finance_company = '$isi->finco'");
            if($cek->num_rows() > 0){
              $t = $cek->row();
              $hasil = $t->total_jual;
            }else{
              $hasil = 0;
            }

            //total semua bulan ini
            $cek_t = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
                AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th'");
            if($cek_t->num_rows() > 0){
              $t = $cek_t->row();
              $hasil_t = $t->total_jual;
            }else{
              $hasil_t = 0;
            }
            
            //cari x
            if($hasil_1 != 0){
              $x_persen = round((($hasil - $hasil_1) / $hasil_1),2);
            }else{
              $x_persen = round(($hasil - $hasil_1),2);
            }          

            //cari y
            if($hasil_t != 0){
              $y_persen = round(($hasil / $hasil_t),2);
            }else{
              $y_persen = round($hasil,2);
            }            
            

           echo "'$isi->finance_company ($x_persen %) - ($y_persen %)'".",";
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
        $sq = $this->db->query("SELECT DISTINCT(tr_spk.id_finance_company) AS finco,ms_finance_company.finance_company FROM tr_spk INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company ORDER BY ms_finance_company.finance_company ASC");
        foreach ($sq->result() as $isi) {
          $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual_1                
              FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
              INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
              WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
              AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th_1' AND tr_spk.id_finance_company = '$isi->finco'");
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
        $sq = $this->db->query("SELECT DISTINCT(tr_spk.id_finance_company) AS finco,ms_finance_company.finance_company FROM tr_spk INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company ORDER BY ms_finance_company.finance_company ASC");
        foreach ($sq->result() as $isi) {
          $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
              FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
              INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
              WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
              AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th' AND tr_spk.id_finance_company = '$isi->finco'");
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

<script type="text/javascript">

var chart1; // globally available
  $(document).ready(function() {
      chart1 = new Highcharts.chart('container3', {
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
          $sq = $this->db->query("SELECT DISTINCT(ms_segment.segment) AS segment,ms_segment.id_segment FROM tr_spk 
              INNER JOIN tr_sales_order ON tr_spk.no_spk = tr_sales_order.no_spk
              INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
              INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment
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
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
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
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
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
              ORDER BY ms_segment.segment ASC");
        foreach ($sq->result() as $isi) {
          $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual_1                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
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
              ORDER BY ms_segment.segment ASC");
        foreach ($sq->result() as $isi) {
          $cek = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS total_jual                
                FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_segment ON ms_tipe_kendaraan.id_segment = ms_segment.id_segment                
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
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
<script type="text/javascript">
  function datatables() {
    $('#table_ajax').DataTable({
      "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
      var monTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
      var tueTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
      var wedTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
       var thuTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
       var friTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

        var satTotal = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

        var senTotal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

        var selTotal = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

        var rabTotal = api
                .column( 10, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

        var kamTotal = api
                .column( 11, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Sub Total');
            $( api.column( 2 ).footer() ).html(monTotal);
            $( api.column( 3 ).footer() ).html(tueTotal);
            $( api.column( 4 ).footer() ).html(wedTotal);
            $( api.column( 5 ).footer() ).html(thuTotal);
            $( api.column( 6 ).footer() ).html(friTotal);
            $( api.column( 7 ).footer() ).html(satTotal);
            $( api.column( 8 ).footer() ).html(senTotal);
            $( api.column( 9 ).footer() ).html(selTotal);
            $( api.column( 10 ).footer() ).html(rabTotal);
            $( api.column( 11 ).footer() ).html(kamTotal);
   
        },
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "scrollX":true,
          fixedHeader:true,
          "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],
          "autoWidth": true
        });
  }
$(document).ready(function() {
    $.ajax({
        beforeSend: function() {
          $('#showDetailStok').html('<tr><td colspan=12 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/showDetailStok')?>",
        type:"POST",
        data:"",            
        cache:false,
        success:function(response){                
           $('#showDetailStok').html(response);
           datatables();
        } 
    })

    // $('#table_ajax').dataTable({
    //   "footerCallback": function ( row, data, start, end, display ) {
    //         var api = this.api(), data;
 
    //         // converting to interger to find total
    //         var intVal = function ( i ) {
    //             return typeof i === 'string' ?
    //                 i.replace(/[\$,]/g, '')*1 :
    //                 typeof i === 'number' ?
    //                     i : 0;
    //         };
 
    //         // computing column Total of the complete result 
    //   var monTotal = api
    //             .column( 2 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );
        
    //   var tueTotal = api
    //             .column( 3 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );
        
    //   var wedTotal = api
    //             .column( 4 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );
        
    //    var thuTotal = api
    //             .column( 5 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );
        
    //    var friTotal = api
    //             .column( 6 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );

    //     var satTotal = api
    //             .column( 7 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );

    //     var senTotal = api
    //             .column( 8 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );

    //     var selTotal = api
    //             .column( 9 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );

    //     var rabTotal = api
    //             .column( 10 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );

    //     var kamTotal = api
    //             .column( 11 )
    //             .data()
    //             .reduce( function (a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0 );
        
    //         // Update footer by showing the total with the reference of the column index 
    //         $( api.column( 0 ).footer() ).html('Sub Total');
    //         $( api.column( 2 ).footer() ).html(monTotal);
    //         $( api.column( 3 ).footer() ).html(tueTotal);
    //         $( api.column( 4 ).footer() ).html(wedTotal);
    //         $( api.column( 5 ).footer() ).html(thuTotal);
    //         $( api.column( 6 ).footer() ).html(friTotal);
    //         $( api.column( 7 ).footer() ).html(satTotal);
    //         $( api.column( 8 ).footer() ).html(senTotal);
    //         $( api.column( 9 ).footer() ).html(selTotal);
    //         $( api.column( 10 ).footer() ).html(rabTotal);
    //         $( api.column( 11 ).footer() ).html(kamTotal);
   
    //     },
    //     "processing": true,
    //     // "serverSide": true,
    //     "scrollX":true,
    //     // "ajax": {
    //     //     "url": "<?php echo site_url('dashboard/ajax')?>",
    //     //     "type": "POST"
    //     // },
    //     // "columnDefs": [
    //     // {
    //     //     "targets": [2,3,4,5,6,7,8,9,10,11], //first column / numbering column
    //     //     "orderable": false, //set not orderable            
    //     // },
    //     ]

    // } );
} );
</script>