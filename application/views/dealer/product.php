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
.mb-10{
  margin-bottom: 2px;
}
</style>
<base href="<?php echo base_url(); ?>" />
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
          <a href="dealer/product">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <div id="row">
          <div class="col-md-12">       
            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">     
              <div class="box-body">              
                <div class="form-group">
                  <div class="col-md-12" align="center">
                    <?php if ($item->preview=='link_youtube'): ?>
                      <iframe width="560" height="315" src="<?= $item->link_youtube ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <?php endif ?>
                    <?php if ($item->preview=='video'): ?>
                      <video width="560" height="315" controls>
                        <?php $video = explode('.',$item->video); ?>
                        <source src="<?= base_url('assets/panel/item_video/'.$item->video) ?>" type="video/<?= count($video)>1?$video[1]:'' ?>">
                      </video>
                    <?php endif ?>
                  </div>
                </div>
                <div class="form-group">
                  <input type="hidden" name="id_penerimaan_unit_dealer">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?= $item->id_item ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Stok</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?= $stok ?>">              
                  </div>
                </div>  
                <div class="form-group">
                  <input type="hidden" name="id_penerimaan_unit_dealer">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?= $item->tipe_ahm ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">ETA</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?= $lead ?>">              
                  </div>
                </div>  
                <div class="form-group">
                  <input type="hidden" name="id_penerimaan_unit_dealer">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?= $item->warna ?>">
                  </div>
                  <input type="hidden" name="id_penerimaan_unit_dealer">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Hot Prospek</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?= $item->hot ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?= mata_uang_rp($item->harga_jual) ?>">              
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Sales Program (Promosi)</button><br><br>
                  </div>
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <thead>
                        <th width="40%">Kode</th>
                        <th>Nama</th>
                      </thead>
                      <tbody>
                        <?php foreach ($sales_program as $sp): ?>
                          <tr>
                            <td><?= $sp->id_program_ahm ?> | <?= $sp->id_program_md ?></td>
                            <td><?= $sp->judul_kegiatan ?></td>
                          </tr>
                        <?php endforeach ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Aksesoris</button><br><br>
                  </div>
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <thead>
                        <th width="40%">Kode</th>
                        <th>Nama</th>
                      </thead>
                      <tbody>
                        <?php foreach ($ksu as $ksu): ?>
                          <tr>
                            <td><?= $ksu->id_ksu ?></td>
                            <td><?= $ksu->ksu ?></td>
                          </tr>
                        <?php endforeach ?>
                      </tbody>
                    </table>
                  </div>
                </div>                
              </div>            
          </div>
        </div>
      </div><!-- /.box-body -->    
    </div><!-- /.box -->  
    

    <?php
    }elseif($set=="index"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">               
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
        <table id="datatable_server" class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
             <th>Kode Item</th>            
             <th>tipe</th>
             <th>Warna</th>
             <th>Harga</th>
             <!-- <th>Bonus Aksesoris</th> -->
             <th>Stok</th>
             <th>Lead Time</th>
             <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<div class="modal fade modalVideo" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Video</h4>
      </div>
      <div class="modal-body">
        show
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){  
  var dataTable = $('#datatable_server').DataTable({  
     "processing":true, 
     "serverSide":true, 
     "language": {                
          "infoFiltered": "",
      }, 
     "order":[],
     "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
     "ajax":{  
          url:"<?php echo site_url('dealer/product/fetch'); ?>",  
          type:"POST",
          dataSrc: "data",
          data: function ( d ) {
            // d.start_date = $('#start_date').val();
            // d.end_date = $('#end_date').val();
            return d;
          },
     },  
     "columnDefs":[  
          { "targets":[3,4,5],"orderable":false},
          { "targets":[3],"className":'text-right'}, 
          // { "targets":0,"checkboxes":{'selectRow':true}}
          { "targets":[6],"className":'text-center'}, 
          // { "targets":[2,4,5], "searchable": false } 
     ],
    //  'select': {
    //    'style': 'multi'
    // },
  });

  });
function showVideo(id_item) {
  $('.modalVideo').modal('show');
}
</script>


<?php } ?>
  </section>
</div>

