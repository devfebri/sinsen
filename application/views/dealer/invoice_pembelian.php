<?php
function bln()
{
  $bulan = $bl = $month = date("m");
  switch ($bulan) {
    case "1":
      $bulan = "Januari";
      break;
    case "2":
      $bulan = "Februari";
      break;
    case "3":
      $bulan = "Maret";
      break;
    case "4":
      $bulan = "April";
      break;
    case "5":
      $bulan = "Mei";
      break;
    case "6":
      $bulan = "Juni";
      break;
    case "7":
      $bulan = "Juli";
      break;
    case "8":
      $bulan = "Agustus";
      break;
    case "9":
      $bulan = "September";
      break;
    case "10":
      $bulan = "Oktober";
      break;
    case "11":
      $bulan = "November";
      break;
    case "12":
      $bulan = "Desember";
      break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 25px;
    padding-left: 4px;
    padding-right: 4px;
  }
</style>
<base href="<?php echo base_url(); ?>" />

<body onload="auto()">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">Customer</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
      <?php
      if ($set == "insert") {
      ?>



      <?php
      } elseif ($set == "view") {
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <!--a href="dealer/monitoring_outstanding_ksu/list_ksu">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> List KSU</button>
          </a-->
              <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->
            </h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body">
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
            <table class="table table-bordered" id="datatable">
              <thead>
                <tr>
                  <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                  <th width="5%">No</th>
                  <!--<th>No PO</th>              -->
                  <th>No Invoice</th>
                  <th>Tgl Invoice</th>
                  <th>Total Amount</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->


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

            var base_url = "<?php echo base_url() ?>"; // You can use full url here but I prefer like this
            $('#datatable').DataTable({
               "pageLength" : 10,
               "serverSide": true,
               "ordering": true, // Set true agar bisa di sorting
                "processing": true,
                "language": {
                  processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                  searchPlaceholder: "Pencarian..."
                },
 
               "order": [[1, "desc" ]],
               "rowCallback": function (row, data, iDisplayIndex) {
                    var info = this.fnPagingInfo();
                    var page = info.iPage;
                    var length = info.iLength;
                    var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                },
               "ajax":{
                        url :  base_url+'dealer/invoice_pembelian/getData',
                        type : 'POST'
                      },
            }); // End of DataTable


          }); 

        </script>


      <?php
      }
      ?>
    </section>
  </div>



  