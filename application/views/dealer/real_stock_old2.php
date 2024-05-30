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
<body onload="auto()">
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
    <li class="">Penerimaan Unit</li>    
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php
    if($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/kekurangan_ksu/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>Action</th>
              <th>Kode Item</th>              
              <th>Tipe</th>              
              <th>Warna</th>
              <th>RFS</th>              
              <th>NRFS</th>              
              <!-- <th>Pinjaman</th> -->              
              <th>Total</th>              
            </tr>            
          </thead>
          <tbody>            
          <?php 
          $no=1;
          $t_rfs=0;$t_nrfs=0;$t_pinj=0;$t_book=0;$tot=0; 
          foreach($dt_real_stock->result() as $row) {               
            $total = $row->stok_rfs + $row->stok_nrfs;

            $cek_th = $this->db->query("SELECT DISTINCT(tahun_produksi) AS tahun FROM tr_fkb WHERE kode_tipe = '$row->id_tipe_kendaraan' AND kode_warna = '$row->id_warna'");
            if($cek_th->num_rows() > 0){
              $th = $cek_th->row();
              $tahun = $th->tahun;
            }else{
              $tahun = "";
            }
            
            echo "
            <tr>
              <td>$no</td>
              <td>
                <a href='dealer/real_stock/detail?id=$row->id_item'>
                  <button type='button' title='Detail' class='btn bg-maroon btn-flat btn-sm'><i class='fa fa-eye'></i> Detail</button>
                </a>
              </td>
              <td>$row->id_item</td>
              <td>$row->tipe_ahm</td>
              <td>$row->warna</td>              
              <td>$row->stok_rfs</td>              
              <td>$row->stok_nrfs</td>              
              <td>$total</td>              
            </tr>
            ";
          $no++;
          $t_rfs  = $t_rfs + $row->stok_rfs;
          $t_nrfs = $t_nrfs + $row->stok_nrfs;
          $tot    = $tot + $total;
          }
          ?>
          </tbody>
          <tfoot>
            <td colspan="5"></td>
            <td><?php echo $t_rfs ?></td>
            <td><?php echo $t_nrfs ?></td>
            <td><?php echo $tot ?></td>
            
          </tfoot>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="detail"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/real_stock">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>          
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
        
        $row = $dt_real_stock->row();
        ?>
        <div class="col-md-12">
          <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->id_item ?>" id="id_niguri" readonly placeholder="Kode Item" name="kode_item">
                </div>              
                <label for="inputEmail3" class="col-sm-2 control-label">QTY RFS</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->stok_rfs ?>" id="id_niguri" readonly placeholder="QTY RFS" name="qty_rfs">                  
                </div>                 
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->tipe_ahm ?>" id="id_niguri" readonly placeholder="Tipe" name="kode_item">
                </div>              
                <label for="inputEmail3" class="col-sm-2 control-label">QTY NRFS</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->stok_nrfs ?>" id="id_niguri" readonly placeholder="QTY NRFS" name="qty_nrfs">                  
                </div>                 
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->warna ?>" id="id_niguri" readonly placeholder="Warna" name="kode_item">
                </div>              
                <!-- <label for="inputEmail3" class="col-sm-2 control-label">QTY Pinjam</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->stok_pinjaman ?>" id="id_niguri" readonly placeholder="QTY Pinjaman" name="qty_nrfs">                  
                </div>                 
              </div> -->
              <!-- <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tahun Produksi</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="2015" id="id_niguri" readonly placeholder="Tahun Produksi" name="kode_item">
                </div>              
                <label for="inputEmail3" class="col-sm-2 control-label">QTY Booking SO</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->stok_booking ?>" id="id_niguri" readonly placeholder="QTY Booking" name="qty_nrfs">                  
                </div>                 
              </div>
              <div class="form-group"> 
                <label for="inputEmail3" class="col-sm-2 control-label"></label>
                <div class="col-sm-4"> 
                  
                </div>               -->
                <label for="inputEmail3" class="col-sm-2 control-label">Total QTY</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->stok_nrfs+$row->stok_rfs+$row->stok_pinjaman ?>" id="id_niguri" readonly placeholder="Total QTY" name="qty_nrfs">                  
                </div>                 
              </div>                                            
              
              <div class="form-group">                
                <button class="btn btn-block btn-success btn-flat" type="button">Detail</button>
                <table id="example1" class="table table-bordered table-hover">
                  <thead>
                    <tr>              
                      <th width="5%">No</th>
                      <th>No Mesin</th>
                      <th>No Rangka</th>              
                      <th>Status</th>                       
                      <th>Status Stok</th>                                   
                    </tr>            
                  </thead>
                  <tbody>            
                  <?php 
                  $no=1; 
                  foreach($dt_pu->result() as $row) {   
                    if($row->status_dealer == 'input'){
                      $status = "<span class='label label-success'>ready</span>";
                    }
                   
                    echo "
                    <tr>
                      <td>$no</td>                      
                      <td>$row->no_mesin</td>
                      <td>$row->no_rangka</td>
                      <td>".strtoupper($row->jenis_pu)."</td>                                    
                      <td>$status</td>                                    
                    </tr>
                    ";
                  $no++;
                  }
                  ?>
                  </tbody>                  
                </table>
              </div>
            </div>
          </form>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>

<script type="text/javascript">
 $(document).ready(function() {

    $('#pj thead th').each( function () {
        var title = $(this).text();
        $(this).html( '<input style="width:95%" type="text" />' );
    } );

  var table = $('#pj').DataTable( {
    responsive: true,
    dom: 'Bfrtip',
    lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],

  buttons: [
  'colvis',
  'pageLength',
    {

        extend: 'print',
        exportOptions: {
            columns: ':visible'
        }
    },
    {
        extend: 'excelHtml5',
        exportOptions: {
            columns: ':visible'
        }
    },
    {
        extend: 'pdfHtml5',
        exportOptions: {
            columns:':visible'
        }
    },    
  ],
          "footerCallback": function ( row, data, start, end, display ) {
              var api = this.api(), data;

              // Remove the formatting to get integer data for summation
              var intVal = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
              };

              // Total over all pages
              var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, 'Rp. ' ).display;
              var noFormat = $.fn.dataTable.render.number( '\,', '.', 2, ' ' ).display;

              // Total over this page
              
               var pageTotalton = api
                          .column( 5, { page: 'current'} )
                          .data()
                          .reduce( function (a, b) {
                              return intVal(a) + intVal(b);
                          }, 0 );
               var pageTotaljum = api
                          .column( 7, { page: 'current'} )
                          .data()
                          .reduce( function (a, b) {
                              return intVal(a) + intVal(b);
                          }, 0 );

              // Update footer
              $( api.column( 5 ).footer() ).html(
                  ' '+noFormat(pageTotalton)
              );
              $( api.column( 7 ).footer() ).html(
                  ''+numFormat (pageTotaljum)
              );              
          }
      } );

      table.columns().every( function () {
          var that = this;

          $( 'input', this.header() ).on( 'keyup change', function () {
              if ( that.search() !== this.value ) {
                  that
                      .search( this.value )
                      .draw();
              }

          } );

      } );


  } );
  </script>

