<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Penjualan Unit</li>
    <li class="">Reminder Follow UP</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>

  <section class="content">
    <?php 
    if($set=="form"){
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='update') {
        $form = 'save_update';
      }
      if ($mode=='assign_supir') {
        $readonly ='readonly';
        // $disabled = 'disabled';
        $form     = 'save_assign';
      }
      if ($mode=='detail') {
        $disabled = 'disabled';
      }
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/reminder_follow_up">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form  class="form-horizontal" id="form_" action="dealer/reminder_follow_up/<?= $form ?>" method="post" enctype="multipart/form-data">
              <?php if (isset($row)): ?>
                <input type="hidden" name="id_reminder" value="<?= $row->id_reminder ?>">
              <?php endif ?>
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal FU</label>
                  <div class="col-sm-4">                   
                    <input type="text" class="form-control datepicker" id="tgl_reminder" name="tgl_reminder" autocomplete="off" value="<?= isset($row)?$row->tgl_reminder:'' ?>" <?= $disabled ?> readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label" style="font-size: 12pt"><b>Status Aktivitas :</b></label>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Metode Follow UP</label>
                  <div class="col-sm-4">                   
                    <input type="text" class="form-control" id="metode_fol_up" name="metode_fol_up" autocomplete="off" value="<?= isset($row)?$row->metode_fol_up:'' ?>" <?= $disabled ?>>
                  </div>
                  <label for="inputEmail3" class="col-sm-1 control-label">Contactable</label>
                  <label for="inputEmail3" class="col-sm-3 control-label">Melakukan Ucapan Terimakasih</label>
                  <div class="col-md-2">
                    <select name="ucapan_terimakasih" id="" class="form-control">
                      <option value="">--choose--</option>
                      <option value="yes">Yes</option>
                      <option value="no">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">                   
                    <input type="text" class="form-control" id="nama_konsumen" name="nama_konsumen" autocomplete="off" value="<?= isset($row)?$row->nama_konsumen:'' ?>" <?= $disabled ?> readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-4 control-label">Reminder KPB</label>
                  <div class="col-md-2">
                    <select name="reminder_pkb" id="" class="form-control">
                      <option value="">--choose--</option>
                      <option value="yes">Yes</option>
                      <option value="no">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nomor Contact</label>
                  <div class="col-sm-4">                   
                    <input type="text" class="form-control" id="no_hp" name="no_hp" autocomplete="off" value="<?= isset($row)?$row->no_hp:'' ?>" <?= $disabled ?> readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-4 control-label">Informasi Tentang Dealer</label>
                  <div class="col-md-2">
                    <select name="info_dealer" id="" class="form-control">
                      <option value="">--choose--</option>
                      <option value="yes">Yes</option>
                      <option value="no">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Tipe Unit</label>
                  <div class="col-sm-4">                   
                    <input type="text" class="form-control" id="desc_unit" name="desc_unit" autocomplete="off" value="<?= isset($row)?$row->desc_unit:'' ?>" <?= $disabled ?> readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label" align="left">Uncontactable</label>
                  <div class="col-md-4">
                    <select name="status_uncontactable" id="status_uncontactable" class="form-control">
                      <option value="">--choose--</option>
                      <option>Failed</option>
                      <option>Unreachable</option>
                      <option>Rejected</option>
                      <option>Workload</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Warna</label>
                  <div class="col-sm-4">                   
                    <input type="text" class="form-control" id="warna" name="warna" autocomplete="off" value="<?= isset($row)?$row->warna:'' ?>" <?= $disabled ?> readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label" align="left">Uncontactable Ke-</label>
                  <div class="col-sm-4">                   
                    <input type="text" class="form-control" id="uncontactable_ke" name="uncontactable_ke" autocomplete="off" value="<?= isset($row)?$row->uncontactable_ke:'' ?>" <?= $disabled ?> readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Sales People</label>
                  <div class="col-sm-4">                   
                    <input type="text" class="form-control" id="id_sales" name="id_sales" autocomplete="off" value="<?= isset($row)?$row->id_sales:'' ?>" <?= $disabled ?> readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Follow Up Berikutnya</label>
                  <div class="col-sm-4">                   
                    <input type="text" class="form-control datepicker" id="tgl_fu_berikutnya" name="tgl_fu_berikutnya" autocomplete="off" value="<?= $mode=='detail'?isset($row)?$row->tgl_fu_berikutnya:'':'' ?>" <?= $disabled ?>>
                  </div>
                </div>
              </div><!-- /.box-body -->
                        
              <div class="box-footer">
                <div class="col-sm-12" align="center">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-refresh"></i> Update FU</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
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
        <table id="datatable_server" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID FU Thanks</th>
              <th>ID Customer</th>
              <th>Nama Customer</th>
              <th>Nomor Contact</th>
              <th>Status Aktivitas Follow UP</th>
              <th>Keterangan</th>
              <th>Aksi</th>
            </tr>
          </thead>          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>
   $(document).ready(function(){  
      var dataTable = $('#datatable_server').DataTable({  
         "processing":true, 
         "serverSide":true, 
         "language": {                
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          }, 
         "order":[],
         "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
         "ajax":{  
              url:"<?php echo site_url('dealer/reminder_follow_up/fetch'); ?>",  
              type:"POST",
              dataSrc: "data",
              data: function (d) {
                  return d;
              },
         },  
         "columnDefs":[  
              // { "targets":[2],"orderable":false},
              { "targets":[2],"className":'text-center'}, 
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // { "targets":[6,7],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
         ],
      });
    });
</script>
    <?php
    }
    ?>
  </section>
</div>