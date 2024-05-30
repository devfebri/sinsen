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
              $dealer = $this->db->query("SELECT COUNT(*) as jum FROM ms_dealer WHERE active = 1")->row();
              echo $dealer->jum;
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
              $kary = $this->db->query("SELECT COUNT(*) as jum FROM ms_karyawan WHERE active = 1")->row();
              echo $kary->jum;
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
              $y = date("d F Y");              
              $f = date("h:i");
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
      <section class="col-lg-12 connectedSortable">
        <div class="box box-success">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Tabel Stok</h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <table class="table table-hovered table-bordered" id="example2">
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
                  <th>Sales Order</th>
                  <th>Stock Days MD</th>
                </tr>
              </thead>
              <tbody>
               <?php 
               $item = $this->db->query("SELECT * FROM ms_item LEFT JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                  WHERE ms_item.active = '1'");
                  //WHERE ms_item.active = '1' LIMIT 0,10");
               foreach ($item->result() as $isi) {
                $cek_ready = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '1' AND tipe='RFS'")->row();
                $cek_booking = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '2'")->row();
                $cek_pl = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND status = '3'")->row();
                $cek_nrfs = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'NRFS' AND status < 4")->row();
                $cek_pinjaman = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode WHERE id_item = '$isi->id_item' AND tipe = 'PINJAMAN' AND status < 4")->row();
                $cek_sl = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list 
                                  WHERE no_shipping_list NOT IN (SELECT no_shipping_list FROM tr_scan_barcode WHERE no_shipping_list IS NOT NULL) 
                                  AND id_modell = '$isi->id_tipe_kendaraan' AND id_warna = '$isi->id_warna'")->row();

                $cek_sl1 = $this->db->query("SELECT COUNT(id_modell) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
                  WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'
                  AND ms_item.bundling <> 'ya'")->row();
                $cek_sl2 = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode INNER JOIN ms_item ON tr_scan_barcode.tipe_motor = ms_item.id_tipe_kendaraan AND tr_scan_barcode.warna = ms_item.id_warna 
                  WHERE tipe_motor = '$isi->id_tipe_kendaraan' AND warna = '$isi->id_warna'
                  AND ms_item.bundling <> 'ya'")->row();      
                $cek_in1 = $this->db->query("SELECT SUM(tr_sipb.jumlah) AS jum FROM tr_sipb INNER JOIN ms_item ON ms_item.id_tipe_kendaraan = tr_sipb.id_tipe_kendaraan AND ms_item.id_warna = tr_sipb.id_warna 
                  WHERE tr_sipb.id_tipe_kendaraan = '$isi->id_tipe_kendaraan' AND tr_sipb.id_warna = '$isi->id_warna'
                  AND ms_item.bundling <> 'ya'")->row();                
                $cek_in2 = $this->db->query("SELECT COUNT(tr_shipping_list.no_mesin) AS jum FROM tr_shipping_list INNER JOIN ms_item ON tr_shipping_list.id_modell = ms_item.id_tipe_kendaraan AND tr_shipping_list.id_warna=ms_item.id_warna
                  WHERE tr_shipping_list.id_modell = '$isi->id_tipe_kendaraan' AND tr_shipping_list.id_warna = '$isi->id_warna'
                  AND ms_item.bundling <> 'ya'")->row();
                $cek_item = $this->db->query("SELECT * FROM ms_item WHERE id_item = '$isi->id_item'")->row();
                $sipb = 0;
                $total = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum  + $cek_pinjaman->jum;
                if($cek_in1->jum - $cek_in2->jum > 0 AND $cek_item->bundling != 'ya'){
                  $rr = $cek_in1->jum - $cek_in2->jum;
                }else{
                  $rr = 0;
                }

                if($cek_sl1->jum - $cek_sl2->jum > 0 AND $cek_item->bundling != 'ya'){
                  $r2 = $cek_sl1->jum - $cek_sl2->jum;
                }else{
                  $r2 = 0;
                }             
                $stok_md = $cek_ready->jum + $cek_booking->jum + $cek_pl->jum + $cek_nrfs->jum + $cek_pinjaman->jum;

                $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                    LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                    LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                    LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                    LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                    LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                    WHERE tr_scan_barcode.id_item = '$isi->id_item' AND tr_scan_barcode.status = '4'")->row();                   
                $cek_unfill = $this->db->query("SELECT COUNT(tr_do_po_detail.id_item) AS jum FROM tr_do_po 
                        LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                        LEFT JOIN tr_picking_list ON tr_picking_list.no_do = tr_do_po.no_do
                        WHERE tr_picking_list.no_picking_list NOT IN (SELECT no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL)                          
                        AND tr_do_po_detail.id_item = '$isi->id_item'")->row();
                $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                        WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
                        AND tr_surat_jalan_detail.id_item = '$isi->id_item'")->row();
                $total_stock = $r2 + $stok_md + $cek_unfill->jum + $cek_in->jum + $cek_qty->jum;
                $stock_market = $stok_md + $cek_unfill->jum + $cek_in->jum + $cek_qty->jum;

                $cek_sales = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                        WHERE tr_scan_barcode.id_item = '$isi->id_item'")->row();
                if($cek_sales->jum != 0){
                  $stock_days = ceil(($stok_md / $cek_sales->jum) * 30);
                }else{
                  $stock_days = ceil(($stok_md) * 30);
                }      

                if($total_stock != 0){                                
                 echo "
                 <tr>
                    <td>$isi->id_item</td>
                    <td>$isi->deskripsi_ahm</td>
                    <td>$rr</td>
                    <td>$r2</td>
                    <td>$stok_md</td>
                    <td>$cek_unfill->jum</td>
                    <td>$cek_in->jum</td>
                    <td>$cek_qty->jum</td>
                    <td>$total_stock</td>
                    <td>$stock_market</td>
                    <td>$cek_sales->jum</td>
                    <td>$stock_days</td>
                 </tr>
                 ";
                  }
                }
               ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
      <section class="col-lg-6 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Rank Dealer - <b><?php echo date("F Y") ?></b></h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <table class="table table-bordered table-hovered" id="example3">
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
                $dealer = $this->db->query("SELECT ms_dealer.*,COUNT(tr_sales_order.no_mesin) AS jum FROM ms_dealer INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan' 
                  GROUP BY tr_sales_order.id_dealer ORDER BY jum DESC LIMIT 0,10");

                $dealer2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM ms_dealer INNER JOIN tr_sales_order ON tr_sales_order.id_dealer = ms_dealer.id_dealer
                  WHERE LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$bulan' 
                  LIMIT 0,10")->row();
                foreach ($dealer->result() as $isi) {                  
                  $kon = ceil(($isi->jum / $dealer2->jum) * 100);
                  $tot = $tot + $isi->jum;
                  $kon2 = $kon2 + $kon;
                  echo "
                  <tr>
                    <td>$no</td>
                    <td>$isi->kode_dealer_md</td>
                    <td>$isi->nama_dealer</td>
                    <td>$isi->jum Unit</td>
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
                  <th><?php echo $kon2 ?></th>                  
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </section>
      <section class="col-lg-6 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Sales Report SSU - <b><?php echo gmdate("d F Y h:i:s", time()+60*60*7); ?></b></h3>
            <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
              <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                    
            </div>
          </div>
          <div class="box-body chat" id="chat-box">
            <table class="table table-bordered table-hovered" id="example5">
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
                $tgl = date("Y-m-d");
                $bulan = date("Y-m");
                $dealer = $this->db->query("SELECT ms_tipe_kendaraan.tipe_ahm,COUNT(tr_sales_order.no_mesin) AS jum,tr_scan_barcode.tipe_motor FROM tr_sales_order 
                  INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
                  INNER JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                  WHERE LEFT(tr_sales_order.tgl_create_ssu,7) = '$bulan'
                  GROUP BY tr_scan_barcode.tipe_motor ORDER BY jum DESC");
                foreach ($dealer->result() as $isi) {
                  $dealer2 = $this->db->query("SELECT COUNT(tr_sales_order.no_mesin) AS jum FROM tr_sales_order 
                    INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin                    
                    WHERE LEFT(tr_sales_order.tgl_create_ssu,10) = '$tgl' AND tr_scan_barcode.tipe_motor = '$isi->tipe_motor'")->row();
                  $t = $t + $dealer2->jum;
                  $b = $b + $isi->jum;
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
                  <th><?php echo $b ?> Unit</th>                  
                </tr>
              </tfoot>
            </table>
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
                    WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 0 AND 0.10
                    AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
                $spk2 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
                    WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 BETWEEN 0.101 AND 0.20
                    AND tr_spk.id_finance_company = '$isi->id_finance_company'")->row();
                $spk3 = $this->db->query("SELECT COUNT(tr_spk.no_spk) AS jum, (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 AS persen FROM tr_spk 
                    WHERE jenis_beli = 'Kredit' AND (tr_spk.dp_stor / tr_spk.harga_tunai) * 100 > 0.20
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
      <section class="col-lg-6 connectedSortable">
        <div class="box box-warning">
          <div class="box-header">
            <i class="fa fa-graphic"></i>
            <h3 class="box-title">Sales Structure By Finance Company</h3>
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
    $sql   = "SELECT ms_finance_company.finance_company,
                COUNT(tr_sales_order.no_mesin) AS total_jual, 
                COUNT(tr_sales_order.no_mesin)/(SELECT COUNT(tr_spk.id_finance_company) FROM tr_spk) * 100 AS finco
              FROM tr_sales_order INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
                INNER JOIN ms_finance_company ON tr_spk.id_finance_company = ms_finance_company.id_finance_company
                WHERE tr_sales_order.tgl_cetak_invoice IS NOT NULL
                AND tr_spk.jenis_beli = 'Kredit' AND LEFT(tr_sales_order.tgl_cetak_invoice,7) = '$th'
                GROUP BY tr_spk.id_finance_company";    
    $cek = $this->db->query($sql);
    foreach ($cek->result() as $r) {    
        $jual=$r->finance_company;
        $jumlah=$r->finco;                    
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