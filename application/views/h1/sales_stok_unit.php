
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
                        <div class="col-12">
                          <div class="table-responsive">
                          <table class="table" id="datatable">
                              <thead>
                                  <tr>
                                      <th>No.</th>
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
                                      <th>Stock Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                  <?php 
                                  $tot = [
                                    'unfill_ahm' => $total->unfill_md,
                                    'int_ahm' => $total->intransit_md,
                                    'stok_md' => $total->stok_md,
                                    'unfill_dealer' => $total->unfill_dealer,
                                    'int_dealer' => $total->intransit_dealer,
                                    'stok_dealer' => $total->stok_dealer,
                                    // 'tot_stok' => $total->unfill_md + $total->intransit_md + $total->stok_md + $total->unfill_dealer + $total->intransit_dealer + $total->stok_dealer,
                                    'tot_stok' => $total->total_stok,
                                    'stok_market' => $total->stok_market,
                                    'sales_dealer' => $total->sales,//$this->m_admin->get_penjualan_inv('bulan', date('Y-m')),
                                    'stok_days' => 0
                                  ];
                                  error_reporting(0);
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
                                   ?>
                                  <th colspan="3" style="text-align: right;">Total</th>
                                  <th><?php echo $tot['unfill_ahm'] ?> </th>
                                  <th><?php echo $tot['int_ahm'] ?> </th>
                                  <th><?php echo $tot['stok_md'] ?> </th>
                                  <th><?php echo $tot['unfill_dealer'] ?> </th>
                                  <th><?php echo $tot['int_dealer'] ?> </th>
                                  <th><?php echo $tot['stok_dealer'] ?> </th>
                                  <th><?php echo $tot['tot_stok'] ?> </th>
                                  <th><?php echo $tot['stok_market'] ?> </th>
                                  <th><?php echo $tot['sales_dealer'] ?> </th>
                                  <th><?php echo $stock_days_r ?> </th>
                                </tfoot>
                            </table>  
                            </div>   
                      </div>

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


<script type="text/javascript">
  $(document).ready(function(e){
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
    {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };

    var base_url = "<?php echo base_url();?>/"; // You can use full url here but I prefer like this
    $('#datatable').DataTable({
      "lengthMenu": [[10, 50, 100, 200, 300], [10, 50, 100, 200, "All"]],
       "pageLength" : 200,
       "serverSide": true,
       "ordering": true, // Set true agar bisa di sorting
        "processing": true,
        "language": {
          processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
          searchPlaceholder: "Masukkan Tipe / Deskripsi..."
        },

       "order": [[12, "desc" ]],
       "rowCallback": function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);
        },
       "ajax":{
                url :  base_url+'h1/sales_stok_unit/getData',
                type : 'POST'
              },
    }); // End of DataTable


  }); 

</script>
