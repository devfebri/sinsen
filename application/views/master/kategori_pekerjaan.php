<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Kategori</li>
    <li class="">Pekerjaan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>

  <section class="content">
    <?php 
    if($set=="form"){
      $form     = '';
      $disabled = '';
      if ($mode=='insert') {
        $form = 'save';
      }
      if ($mode=='edit') {
        $form = 'save_edit';
        $row = $row->row();
      }
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kategori_pekerjaan">
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
            <form class="form-horizontal" id="form_" action="master/kategori_pekerjaan/<?= $form ?>" method="post" enctype="multipart/form-data">
              <?php if (isset($row->id)): ?>
                <input type="hidden" value="<?= $row->id ?>" name="id">
              <?php endif ?>
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kategori</label>
                  <div class="col-sm-4">
                    <input type="text" autocomplete="off" required class="form-control" id="id_kategori" name="id_kategori" value="<?= isset($row->id_kategori)?$row->id_kategori:'' ?>">
                  </div>        
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                  <div class="col-sm-4">
                    <input type="text" autocomplete="off" required class="form-control" name="kategori" value="<?= isset($row->kategori)?$row->kategori:'' ?>">
                  </div>
                </div>  
                          
                    
                

              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-8">
                  <button type="submit" name="submit" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>               
                </div>
              </div><!-- /.box-footer -->
    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/kategori_pekerjaan/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID Kategori</th>
              <th>Kategori</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data->result() as $rs):  ?>
              <tr>
                <td><?= $rs->id_kategori ?></td>
                <td><?= $rs->kategori ?></td>
                <td align="center">
                  <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-xs btn-flat" href="master/kategori_pekerjaan/delete?id=<?php echo $rs->id ?>"><i class="fa fa-trash-o"></i></a>
                  <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-xs btn-flat' href='master/kategori_pekerjaan/edit?id=<?php echo $rs->id ?>'><i class='fa fa-edit'></i></a>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>