<?php 
function bln(){
  $bulan=$bl=$month=date("m");
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
<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>
<base href="<?php echo base_url(); ?>" />
<?php 
if(isset($_GET['id'])){
?>
<body onload="cek_jenis()">
<?php }else{ ?>
<body onload="auto()">
<?php } ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Pembelian</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
<?

  if($set=="log"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
        <div class="col-xs-6">

          <a href="/h1/shipping_list/">
            <button class="btn bg-maroon btn-flat margin pull-left"><i class="fa fa-arrow-left"></i> Back</button>
          </a>

        </div>
                    
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

<div class="table-responsive">


<table id="tbl_set_ev" class="table table-bordered table-hover">
        <thead>
            <tr>
            <th>No</th>
             <th>Date</th>
             <th>From API </th>
             <!-- <th>Api Key</th> -->
             <th>endpoint</th>
             <th>post data</th>
             <th>Status</th>
             <th>return Api</th>
             <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
      </div>

    </div>
        
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p><strong>Status</strong> <span class="modal-status"></span></p>
            <p><span class="modal-message"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
<script>
  $(document).ready(function() {
   $('#tbl_set_ev').DataTable({
        "searchable": false,
        "processing": true, 
        "serverSide": true, 
        "order": [],
        "ajax": {
          "url": "<?php echo site_url('/h1/shipping_list/fetch_api_ev') ?>",
          "type": "POST",
          data: function(d) {
            return d;
          },
        },
        "columnDefs": [{
          "targets": [0], 
          "orderable": false, 
        }, ],
      });

      $(document).on('click', '.show-details', function() {
        var status = $(this).data('status');
        var message = JSON.stringify($(this).data('message'), null, 2);
        $('.modal-title').text('Post Data');
        $('.modal-status').text(status);
        $('.modal-message').text(message);
        $('#myModal').modal('show');
      });

    $(document).on('click', '.show-result', function() {
        var status = $(this).data('status');
        var message = JSON.stringify($(this).data('message'), null, 2);
        $('.modal-title').text('Return API');
        $('#modal-status').text(status);
        $('.modal-message').text(message);
        $('#myModal').modal('show');
    });

  });
</script>



    
    <?php 
  }elseif($set=="ev"){
  ?>
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">

          <a href="h1/shipping_list">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
          </a> 


        <!-- <a href="h1/shipping_list/rem">
          <button class="btn bg-yellow btn-flat margin"></i>REM</button>
        </a>    -->
     
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


      <table id="tableSLU" class="table table-bordered table-hover data-table tableSLU">
      <thead>
          <tr>
            <th>No.SL</th>              
            <th>Tgl.SL</th>              
            <th>No.SIPB</th>              
            <th>No.Mesin</th>
            <th>No.Rangka</th>              
            <th>Model</th>
            <th>Warna</th>
            <th>Cabang</th>
            <th>No.Pol Eks</th>
            <th>MD QQ</th>
            <th>MD PO</th>
            <th>No.Mesin Lengkap</th>
            <th>No.Frame Awal</th>
            <th>Nama Eks</th>
            <th>Kota Tujuan</th>              
          </tr>
        </thead>
      </table>

    </div><!-- /.box-body -->
  </div><!-- /.box -->

  <script>

    $(document).ready(function() {
        tableSLU();
      });
  </script>


   <script>
  function tableSLU() {   
    
      $("#tableSLU").show();

      $('.tableSLU').DataTable({
        processing: true,
        serverSide: true,
        "scrollX":true,
        "language": {
          "infoFiltered": "",
          "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
        },
        order: [],
        ajax: {
          url: "<?= base_url('h1/shipping_list/fetch_ev') ?>",
          dataSrc: "data",
          data: function(d) {
            d.tgl_mohon_samsat = $('#tgl_mohon_samsat').val();
            d.no_mesin = $('#no_mesin').val();
            d.no_faktur_ahm = $('#no_faktur_ahm').val();
            d.id_dealer = $('#id_dealer').val();
            return d;
          },
          type: "POST"
        },
        "columnDefs": [{
            "targets": [4],
            "orderable": false
          },
          {
            "targets": [4],
            "className": 'text-center'
          },
          {
            "targets": [4],
            "searchable": false
          }
        ]
      });
    }

  </script> 


<script>
      $("#tableSLB").show();

    function tableSLB() {
      $('.tableSLB').DataTable({
        processing: true,
        serverSide: true,
        "scrollX":true,
        "language": {
          "infoFiltered": "",
          "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
        },
        order: [],
        ajax: {
          url: "<?=  base_url('h1/shipping_list/fetch_api') ?>",
          dataSrc: "data",
          data: function(d) {
            return d;
          },
          type: "POST"
        },
        "columnDefs": [{
            "targets": [4],
            "orderable": false
          },
          {
            "targets": [4],
            "className": 'text-center'
          },
          {
            "targets": [4],
            "searchable": false
          }
        ]
      });

      };

  
    </script>


  <?php
  }elseif($set=="upload"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/shipping_list">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/shipping_list/import_db" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                  <div class="col-sm-10">
                    <input type="file" accept=".SL, .KSL" required class="form-control" autofocus name="userfile">                    
                  </div>                  
                </div>                                                                                                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to import this data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Start Upload</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php
    }elseif($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
   
          <a href="h1/shipping_list/upload">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
            </a>   
            
            <a href="h1/shipping_list/ev">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-bolt"></i> UNIT EV</button>
            </a>     

            <a href="h1/shipping_list/oem">
              <button class="btn bg-yellow btn-flat margin"><i class="fa fa-send"></i> API OEM (AHM)</button>
            </a>     

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
        <table id="tbl_sl" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <!-- <th width="5%">No</th> -->
              <th>No.SL</th>              
              <th>Tgl.SL</th>              
              <th>No.SIPB</th>              
              <th>No.Mesin</th>
              <th>No.Rangka</th>              
              <th>Model</th>
              <th>Warna</th>
              <th>Cabang</th>
              <th>No.Pol Eks</th>
              <th>MD QQ</th>
              <th>MD PO</th>
              <th>No.Mesin Lengkap</th>
              <th>No.Frame Awal</th>
              <th>Nama Eks</th>
              <th>Kota Tujuan</th>              
            </tr>
          </thead>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function() {
        $('#tbl_sl').DataTable({
          processing: true,
          serverSide: true,
          "scrollX":true,
          "language": {
            "infoFiltered": "",
            "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          },
          order: [],
          ajax: {
            url: "<?= base_url('h1/shipping_list/fetch') ?>",
            dataSrc: "data",
            data: function(d) {
              d.tgl_mohon_samsat = $('#tgl_mohon_samsat').val();
              d.no_mesin = $('#no_mesin').val();
              d.no_faktur_ahm = $('#no_faktur_ahm').val();
              d.id_dealer = $('#id_dealer').val();
              return d;
            },
            type: "POST"
          },
          "columnDefs": [{
              "targets": [4],
              "orderable": false
            },
            {
              "targets": [4],
              "className": 'text-center'
            },
            {
              "targets": [4],
              "searchable": false
            }
          ]
        });
      });

      function loads() {
        $('#tbl_sl').DataTable().ajax.reload();
      }
    </script>
    <?php
    }
elseif($set=="oem"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">

        <a href="h1/shipping_list/">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
          </a> 
   

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
        <table id="tbl_sl" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>No. Shipping List</th>
              <th>Tgl. Shipping List</th>              
              <th>Kode MD </th>
              <th>Tipe</th>              
              <th>Kode Part </th>              
              <th>Nama Part</th>              
              <th>Serial Number</th>              
              <th>From API </th>
              <th>Send API Created</th>
              <th>Tgl Penerimaan</th>
              <th>No Penerimaan</th>
            </tr>
          </thead>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>

      $(document).ready(function() {
        $('#tbl_sl').DataTable({
          processing: true,
          serverSide: true,
          "scrollX":true,
          "language": {
            "infoFiltered": "",
            "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          },
          order: [],
          ajax: {
            url: "<?= base_url('h1/shipping_list/fetch_api') ?>",
            dataSrc: "data",
            data: function(d) {
              return d;
            },
            type: "POST"
          },
          "columnDefs": [{
              "targets": [4],
              "orderable": false
            },
            {
              "targets": [4],
              "className": 'text-center'
            },
            {
              "targets": [4],
              "searchable": false
            }
          ]
        });
      });

      function loads() {
        $('#tbl_sl').DataTable().ajax.reload();
      }
    </script>
    <?php
    }
    ?>
  </section>
</div>


