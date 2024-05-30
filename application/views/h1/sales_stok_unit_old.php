
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Sales & Stok Unit</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
          	<?php                       
		        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
		        ?>                  
		        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
		            <strong><?php echo $_SESSION['pesan'] ?></strong>
		            <button class="close" data-dismiss="alert">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">Close</span>  
		            </button>
		        </div>
		        <?php
		        }
		            $_SESSION['pesan'] = '';                        
		                
		        ?>
              <div class="box-body">
             
			                                                         
                             
              </div><!-- /.box-body -->              
              <div class="box-footer"> 

                  <div class="row">
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
                        <div class="row" style="margin-bottom: 20px;">
                          <div class="col-md-4">
                            <form action="" class="form-inline" method="GET">
                              <select name="kolom" class="form-control" style="width: 100%">
                                  <option value="tipe">Tipe</option>
                                  <option value="deskripsi">Deskripsi</option>
                                  <option value="unfill_ahm">Unfill ahm</option>
                                  <option value="int_ahm">Int ahm</option>
                                  <option value="stok_md">Stok md</option>
                                  <option value="unfill_dealer">Unfill dealer</option>
                                  <option value="int_dealer">Int dealer</option>
                                  <option value="stok_dealer">Stok dealer</option>
                              </select>
                              <div class="input-group">
                                  <select name="type_order" class="form-control" style="width: 100%">
                                    <option value="asc">ASC</option>
                                    <option value="desc">DESC</option>
                                  </select>
                                  <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">Pilih</button>
                                  </span>
                              </div>
                            </form>
                          </div>
                          <div class="col-md-4"></div>
                          <div class="col-md-4">
                            <form action="" method="GET">
                              <div class="input-group">
                                <input type="text" name="tipe_ahm" class="form-control" placeholder="Cari Tipe/Deskripsi..." value="<?php echo (isset($_GET['tipe_ahm'])) ? $_GET['tipe_ahm'] : '' ?>" >
                                <span class="input-group-btn">
                                  <?php if (isset($_GET['tipe_ahm'])): ?>
                                    <a href="<?php echo base_url('h1/sales_stok_unit') ?>" class="btn btn-danger">Reset</a>
                                  <?php endif ?>
                                  <button class="btn btn-primary" type="submit">Cari</button>
                                </span>
                              </div>
                            </form>
                          </div>
                          
                        </div>
                        <div class="table-responsive">
                          <table class="table table-bordered table-hovered table-striped " id="showDetailStok" style="text-align:center">
                        <?php 
                       
                          $data = array();
                          $no = 1;
                          // while($row=mysqli_fetch_array($query) ) {  // preparing an array
                          if (isset($_GET['download'])) {
                            header("Content-type: application/octet-stream");
                            header("Content-Disposition: attachment; filename=Sales_&_Stock_Unit.xls");
                            header("Pragma: no-cache");
                            header("Expires: 0");
                            echo '<p align="center" style="font-weight:bold">Sales & Stock Unit</p>';
                            echo '<table border=1>';
                          }
                          echo '
                          <thead>
                          <tr>
                          <th style="text-align:center;">Tipe</th>
                          <th style="text-align:center;">Deskripsi</th>
                          <th style="text-align:center;">Unfill AHM</th>
                          <th style="text-align:center;">Int AHM</th>
                          <th style="text-align:center;">Stok MD</th>
                          <th style="text-align:center;">Unfill Dealer</th>
                          <th style="text-align:center;">Int Dealer</th>
                          <th style="text-align:center;">Stok Dealer</th>
                          <th style="text-align:center;">Total Stok</th>
                          <th style="text-align:center;">Stok Market</th>
                          <th style="text-align:center;">Sales Dealer</th>
                          <th style="text-align:center;">Stock Days</th>
                          </tr>
                          </thead>
                          <tbody>
                          ';
                          $tot = [
                            'unfill_ahm' => $total->unfill_md,
                            'int_ahm' => $total->intransit_md,
                            'stok_md' => $total->stok_md,
                            'unfill_dealer' => $total->unfill_dealer,
                            'int_dealer' => $total->intransit_dealer,
                            'stok_dealer' => $total->stok_dealer,
                            'tot_stok' => $total->unfill_md + $total->intransit_md + $total->stok_md + $total->unfill_dealer + $total->intransit_dealer + $total->stok_dealer,
                            'stok_market' => $total->stok_md + $total->unfill_dealer + $total->intransit_dealer + $total->stok_dealer,
                            'sales_dealer' => $total->sales,//$this->m_admin->get_penjualan_inv('bulan', date('Y-m')),
                            'stok_days' => 0
                          ];
                          foreach ($query->result() as $row) {
                            $no++;
                            $id_tipe_kendaraan = $row->id_tipe_kendaraan;
                            $tipe_ahm = $row->tipe_ahm;
                            
                            $rr = $row->unfill_md;
                            $r2 = $row->intransit_md;
                            $stok_md = $row->stok_md;
                            $unfill = $row->unfill_dealer;
                            $total_stock = $row->unfill_md + $row->intransit_md + $row->stok_md + $row->unfill_dealer + $row->intransit_dealer + $row->stok_dealer;
                            $stock_market = $row->stok_md + $row->unfill_dealer + $row->intransit_dealer + $row->stok_dealer;
                            
                            // $cek_sales = $this->m_admin->get_penjualan_inv('bulan', $tgl_bln, $row->id_tipe_kendaraan);
                            $cek_sales = $this->m_admin->get_penjualan_inv('bulan', date('Y-m'), $row->id_tipe_kendaraan);
                            $tg = date('d');
                            $stock_r    = @($stock_market / $total->sales) * $tg;
                            $stock_day = round($stock_r, 2);
                            $pecah  = explode(".", $stock_day);

                            if (isset($pecah[1])) {
                              if ($pecah[1] / 100 > 0.5) {
                                $stock_day_r = ceil($stock_day);
                              } else {
                                $stock_day_r = floor($stock_day);
                              }
                            } else {
                              $stock_day_r = $stock_day;
                            }
                            $stock_days = ceil(@($stock_market / $cek_sales));

                            // if($total_stock + $cek_sales > 0){  
                              echo "
                              <tr>
                              <td>$id_tipe_kendaraan</td>
                              <td>$tipe_ahm</td>
                              <td>$rr</td>
                              <td>$r2</td>
                              <td>$stok_md</td>
                              <td>$unfill</td>
                              <td>$row->intransit_dealer</td>
                              <td>$row->stok_dealer</td>
                              <td>$total_stock</td>
                              <td>$stock_market</td>
                              <td>$cek_sales</td>
                              <td>$stock_day_r</td>
                              </tr>
                              ";
                              // $tot['unfill_ahm']    += $rr;
                              // $tot['int_ahm']       += $r2;
                              // $tot['stok_md']       += $stok_md;
                              // $tot['unfill_dealer'] += $unfill;
                              // $tot['int_dealer']    += $row->intransit_dealer;
                              // $tot['stok_dealer']   += $row->stok_dealer;
                              // $tot['tot_stok']      += $total_stock;
                              // $tot['stok_market']   += $stock_market;
                              // $tot['sales_dealer']  += $cek_sales;
                            // }
                          }
                          echo '</tbody>';
                          echo '<tfoot>';
                          echo '<th colspan=2 style="text-align:center;">Total</th>';
                          echo '<th style="text-align:center;">' . $tot['unfill_ahm'] . '</th>';
                          echo '<th style="text-align:center;">' . $tot['int_ahm'] . '</th>';
                          echo '<th style="text-align:center;">' . $tot['stok_md'] . '</th>';
                          echo '<th style="text-align:center;">' . $tot['unfill_dealer'] . '</th>';
                          echo '<th style="text-align:center;">' . $tot['int_dealer'] . '</th>';
                          echo '<th style="text-align:center;">' . $tot['stok_dealer'] . '</th>';
                          echo '<th style="text-align:center;">' . $tot['tot_stok'] . '</th>';
                          echo '<th style="text-align:center;">' . $tot['stok_market'] . '</th>';
                          echo '<th style="text-align:center;">' . $tot['sales_dealer'] . '</th>';
                          $tg = date('d');
                          $stock_d    = ($tot['stok_market'] / $tot['sales_dealer']) * $tg;
                          $stock_days = round($stock_d, 2);
                          $pecah  = explode(".", $stock_days);
                          if (isset($pecah[1])) {
                            if ($pecah[1] / 100 > 0.5) {
                              $stock_days_r = ceil($stock_days);
                            } else {
                              $stock_days_r = floor($stock_days);
                            }
                          } else {
                            $stock_days_r = $stock_days;
                          }
                          echo '<th style="text-align:center;">' . $stock_days_r . '</th>';
                          echo '</tfoot>';
                          if (isset($_GET['download'])) {
                            echo '</table>';
                          }

                

                         ?>
                         </table>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <?php if (!isset($_GET['tipe_ahm'])): ?>
                              <?php echo $pagination ?>
                            <?php endif ?>
                            
                          </div>
                        </div>
                        <!-- <center>
                          <a href="#" class="btn btn-primary" id="btnShowDetailStok">Tampilkan Data</a>
                        </center>
                        <div class="table-responsive">
                          <table class="table table-bordered table-hovered table-striped " id="showDetailStok" style="text-align:center">
                          </table>
                        </div> -->
                      </div>
                    </div>
                  </div>
                </div>                                                  
                
              </div>
          </div>
        </div>
      </div>
    </div><!-- /.box -->


  </section>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("#btnShowDetailStok").click(function(e) {
      e.preventDefault();

      //simpan jam
      localStorage.setItem("klikStokDashboard", "<?php echo date('d F Y H:i:s') ?>");

      getStokMD();
      $(this).hide();
    });
  });
  function loadDatatables(el) {
    scrolly = 250;
    ordering = false;
    order = [];

    if (el == 'tabelSalesDealerGroup') {
      var scrolly = 170;
    }
    if (el == 'tabelSalesFincoByDP') {
      var scrolly = 193;
    }
    if (el == 'tabelSalesCompDistrict') {
      var scrolly = 122;
    }

    console.log(order);
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
        // 'autoWidth': true,

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
        // loadDatatables('showDetailStok');
        $("#waktuKlik").text(waktuKlik);
      }
    })
  }
</script>
