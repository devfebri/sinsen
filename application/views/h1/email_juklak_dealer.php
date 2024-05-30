<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 40px;
    padding-left: 5px;
    padding-right: 5px;
    margin-right: 0px;
  }

  .isi_combo {
    height: 30px;
    border: 1px solid #ccc;
    padding-left: 1.5px;
  }

  .center-table {
    text-align: center;
    align-items: center;
  }
</style>

<base href="<?php echo base_url(); ?>" />

<body>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H1</li>
        <li class="">Business Control</li>
        <li class="active">Email Juklak Dealer</li>
      </ol>
    </section>
    <section class="content">

      <?php
    if($set=="view"){
    ?>

      <div class="box">
        <div class="box-header with-border">
          <hr>
        <div class="row">
              <div class="col-md-3">
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3><?php echo $count ?></h3>
                    <p>Total Cc Email Aktif</p>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="small-box bg-primary">
                  <div class="inner">
                    <div class="row">
                    <div class="col-md-4">
                    <h6>Batch 1 : <b> <?php echo $batch_satu?>  (<?php echo $batch_satu+$count?></b>) <?php if(($batch_satu+$count) > 50){echo '<i class="fa fa-exclamation-circle" aria-hidden="true" title="Data email batch melebihi 50"></i>';} ?></h6>
                    <h6>Batch 2 :<b> <?= $batch_dua?> (<?php echo $batch_dua+$count?></b>)  <?php if(($batch_dua+$count) > 50){echo '<i class="fa fa-exclamation-circle" aria-hidden="true" title="Data email batch melebihi 50"></i>';} ?> </h6>
                    <h6>Batch 3 : <b> <?= $batch_tiga?>   (<?php echo $batch_tiga+$count?></b>)    <?php if(($batch_tiga+$count) > 50){echo '<i class="fa fa-exclamation-circle" aria-hidden="true" title="Data email batch melebihi 50"></i>';} ?> </b></h6>
                    </div>
                    <div class="col-md-3">
                    <h6>Batch 4 :  <b> <?= $batch_empat?>  (<?php echo $batch_empat+$count?></b>)   <?php if(($batch_empat+$count) > 50){echo '<i class="fa fa-exclamation-circle" aria-hidden="true" title="Data email batch melebihi 50"></i>';} ?></b> </h6>
                    <h6>Batch 5 :  <b> <?= $batch_lima?>  (<?php echo $batch_lima+$count?></b>)   <?php if(($batch_lima+$count)  > 50){echo '<i class="fa fa-exclamation-circle" aria-hidden="true" title="Data email batch melebihi 50"></i>';} ?> </b></h6>
                    <h6>Batch 6 :  <b> <?= $batch_enam?>  (<?php echo $batch_enam+$count?></b>)   <?php if(($batch_enam+$count)  > 50){echo '<i class="fa fa-exclamation-circle" aria-hidden="true" title="Data email batch melebihi 50"></i>';} ?> </b></h6>
                    </div>
                    <div class="col-md-5">
                    <h6>Total email Batch :  <b> <?= $totalbatch = $batch_enam + $batch_lima + $batch_empat + $batch_tiga + $batch_dua + $batch_satu?> </b></h6>
                    </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="col-md-3">
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3><?php echo $count_email_dealer ?></h3>
                    <p> Total Email Dealer</p>
                  </div>
                </div>
              </div>
              
            </div>
          <h3 class="box-title">
            <a href="/h1/email_juklak_dealer_cc" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-envelope"
                aria-hidden="true"></i>
              Email Cc</a>

              <a href="/h1/email_juklak_dealer/reset" onclick="return confirm('Apakah anda yakin ingin seting ulang Batch email?')" class="btn btn-warning btn-sm btn-flat"><i class="fa fa-history"
                aria-hidden="true"></i>
              Reset Batch</a>
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



          <table id="example4" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="1%">No</th>
                <th>Kode Dealer</th>
                <th>Nama Dealer</th>
                <th>Batch</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php 
          $no=1; 
          foreach($dt_dealer->result() as $row) {                                         
            ?>
              <tr>
                <td><?= $no?></td>
                <td><?= $row->kode_dealer_md?></a></td>
                <td><?= $row->nama_dealer?></a></td>
                <td><?= $row->batch?></a></td>
                <td class="center-table">
                  <a href="h1/email_juklak_dealer/email_juklak?id=<?= $row->id_dealer  ?>"
                  class="btn btn-sm btn-primary" title="Detail Email" > <i class="fa fa-envelope"></i></a>
                  <button class="btn btn-default btn-sm btn-flat" title="set Batch" data-toggle="modal"  id="selectbatch"  data-kd_md="<?=$row->kode_dealer_md?>" data-nama_dl="<?=$row->nama_dealer?>" data-dealer_id_email="<?=$row->id_dealer?>" data-target="#Modalbatch"><i class="fa fa-cog" aria-hidden="true"></i></button>
                </td>
                <?php                                  
          $no++;
          }
          ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->



      <div class="modal fade" id="Modalbatch" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Set Batch </h4>
            </div>
            <div class="modal-body">
              <form action="h1/email_juklak_dealer/setbatch" method="post">

              <div class="form-group">
                  <label for="email">ID Dealer</label>
                  <input type="text" class="form-control" value=""  name="dealer_id" id="dealer_id" readonly>
                  <input type="hidden" class="form-control" value=""  name="dealer_id_email" id="dealer_id_email" readonly>
                </div>

                <div class="form-group">
                  <label for="email">Nama Dealer</label>
                  <input type="text" class="form-control" value="" name="dealer_name" id="dealer_name" readonly>
                </div>

                <div class="form-group">
                  <label for="email">Batch</label>
                  <select name="batch" id="batch" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="NULL">Unset Batch</option>
                  </select>
                </div>


            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
          </div>
        </div>
      </div>


      <?php
    }
    ?>
      <?php
    if($set=="detail"){
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h1/email_juklak_dealer/">
              <button class="btn bg-maroon  btn-sm btn-flat margin"><i class="fa fa-history"></i> Kembali</button>
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
          <form class="form-horizontal" action="h1/monitor_displan/filter" method="post" enctype="multipart/form-data">
            <div class="box-body">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" value="<?= $dt_dealer_header->nama_dealer?>" disabled>
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer MD</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" value="<?= $dt_dealer_header->kode_dealer_md?>" disabled>
                </div>
              </div>


          </form>

          <hr>

          <span id="tampil_data"></span>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

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

          <div class="container">
            <div class="form-group">
              <button class="btn btn-primary btn-sm btn-flat" id="select" data-toggle="modal" data-target="#modaladd"><i
                  class="fa fa-plus" aria-hidden="true"></i> Tambah <Datag></Datag></button>
            </div>
          </div>
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Email</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php 
          $no=1; 
          foreach($dt_dealer_show->result() as $row) {                                         
            ?>
              <tr>
                <td><?= $no++;?></td>
                <td><?= $row->email?></td>
                <td>
                  <?php if (empty($row->active)) {?>
                  <i class="fa fa-ban" aria-hidden="true"></i>
                  <?php }else{ ?>
                  <i class="fa fa-check" aria-hidden="true"></i>
                  <?php
                } 
                   ?>
                </td>
                <td class="center-table">
                  <button class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#Modaledit"
                    id="select" data-email="<?=$row->email?>" data-id="<?=$row->id?>" data-active="<?=$row->active?>"
                    data-dealer="<?=$row->id_dealer?>"><i class="fa fa-edit" aria-hidden="true"></i></button>

                  <a data-toggle='tooltip' title="Delete Data"
                    onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat"
                    href="h1/email_juklak_dealer/delete?id=<?php echo $row->id ?>&dealer=<?php echo $row->id_dealer ?>"><i
                      class="fa fa-trash-o"></i></a>
                </td>
                </td>
                <?php                                  
          }
          ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->


      <div class="modal fade" id="Modaledit" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Ubah Data </h4>
            </div>
            <div class="modal-body">
              <form action="h1/email_juklak_dealer/edit" method="post">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="hidden" class="form-control" value="" name="id_email" id="email_id">
                  <input type="text" class="form-control" value="" autofocus="" name="email_juklak" id="email" required>
                  <input type="hidden" class="form-control" value="" name="active" id="active">
                  <input type="hidden" class="form-control" value="" name="dealer" id="dealer">
                </div>

                <div class="checkbox">
                  <label><input type="checkbox" name="status_active" class="active"> Status</label>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
          </div>
        </div>
      </div>


      <div class="modal fade" id="modaladd" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Tambah Data </h4>
            </div>
            <div class="modal-body">
              <form action="h1/email_juklak_dealer/add" method="post">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="hidden" class="form-control" value="<?= $dt_dealer_header->id_dealer?>" autofocus=""
                    required="" placeholder="Email" name="dealer">
                  <input type="email" class="form-control" value="" autofocus="" required="" placeholder="Email"
                    name="email_juklak">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <?php
}
?>

    </section>
  </div>

  <script>
    $(document).ready(function () {
      $(document).on('click', '#select', function () {
        var item_id = $(this).data('id');
        var email = $(this).data('email');
        var dealer = $(this).data('dealer');
        var active = $(this).data('active');
        if (active == 1) {
          $('.active').prop('checked', true);
        }
        $('#email_id').val(item_id);
        $('#email').val(email);
        $('#dealer').val(dealer);
        $('#modal-item').modal('hide');
        
      });
    });
  </script>


<script>
    $(document).ready(function () {
      $(document).on('click', '#selectbatch', function () {
        var batch_kd_md= $(this).data('kd_md');
        var batch_nama_dealer= $(this).data('nama_dl');
        var batch_dealer_id_email= $(this).data('dealer_id_email');

        // alert(batch_nama_dealer);

        $('#dealer_name').val(batch_nama_dealer);
        $('#dealer_id').val(batch_kd_md);
        $('#dealer_id_email').val(batch_dealer_id_email);
        $('#modal-item').modal('hide');


        


      });
    });
  </script>

<script>
$(document).ready(function() {

});
</script>