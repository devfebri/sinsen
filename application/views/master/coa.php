<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Master Finance</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/coa">
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
            <form class="form-horizontal" action="master/coa/save" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode COA</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" autofocus id="inputEmail3" placeholder="Kode COA" name="kode_coa">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">COA</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="COA" name="coa">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Transaksi</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tipe_transaksi">
                      <option value="">- choose -</option>
                      <option>Debit</option>
                      <option>Kredit</option>
                    </select>
                  </div>
                </div>                                                                                      
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Saldo Awal</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Saldo Awal" name="saldo_awal">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Digunakan Untuk</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="digunakan_utk">
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_head_account");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->kode'>$isi->kode</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>            
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $dt_coa->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/coa">
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
            <form class="form-horizontal" action="master/coa/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->kode_coa ?>">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Kode COA</label>
                <div class="col-sm-4">
                  <input value="<?php echo $row->kode_coa ?>" type="text" class="form-control" autofocus id="inputEmail3" placeholder="Kode COA" name="kode_coa">
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">COA</label>
                <div class="col-sm-4">
                  <input value="<?php echo $row->coa ?>" type="text" class="form-control" id="inputEmail3" placeholder="COA" name="coa">
                </div>
              <div class="form-group">
              </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Tipe Transaksi</label>
                <div class="col-sm-4">
                  <select class="form-control" name="tipe_transaksi">
                    <option value="<?php echo $row->tipe_transaksi ?>"><?php echo $row->tipe_transaksi ?></option>
                    <?php 
                    if($row->tipe_transaksi == 'Debit'){
                      echo "<option>Kredit</option>";
                    }elseif($row->tipe_transaksi == 'Kredit'){
                      echo "<option>Debit</option>";
                    }else{
                      echo "<option value=''>- choose -</option>
                            <option>Debit</option>
                            <option>Kredit</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>                                                                                      
              <div class="form-group">  
                <label for="inputEmail3" class="col-sm-2 control-label">Saldo Awal</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" value="<?php echo $row->saldo_awal ?>" id="inputEmail3" placeholder="Saldo Awal" name="saldo_awal">
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Digunakan Untuk</label>
                <div class="col-sm-4">
                  <select class="form-control" name="digunakan_utk">
                    <option><?php echo $row->digunakan_utk ?></option>
                    <?php 
                    $r = $this->m_admin->getAll("ms_head_account");
                    foreach ($r->result() as $isi) {
                      echo "<option value='$isi->kode'>$isi->kode</option>";
                    }
                    ?>
                  </select>
                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                
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
          <a href="master/coa/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                
        ?>
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>Kode COA</th>
              <th>COA</th>
              <th>Tipe Transaksi</th>                            
              <th>Saldo Awal</th>
              <th>Digunakan untuk</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_coa->result() as $row) {                 
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->kode_coa</td>              
              <td>$row->coa</td>              
              <td>$row->tipe_transaksi</td>                        
              <td>$row->saldo_awal</td>                        
              <td>$row->digunakan_utk</td>                                      
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/coa/delete?id=<?php echo $row->kode_coa ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/coa/edit?id=<?php echo $row->kode_coa ?>'><i class='fa fa-edit'></i></a>
              </td>
            </tr>
          <?php
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>

<script type="text/javascript">
function bulk_delete(){
  var list_id = [];
  $(".data-check:checked").each(function() {
    list_id.push(this.value);
  });
  if(list_id.length > 0){
    if(confirm('Are you sure delete this '+list_id.length+' data?'))
      {
        $.ajax({
          type: "POST",
          data: {id:list_id},
          url: "<?php echo site_url('master/coa/ajax_bulk_delete')?>",
          dataType: "JSON",
          success: function(data)
          {
            if(data.status){
              window.location.reload();
            }else{
              alert('Failed.');
            }                  
          },
          error: function (jqXHR, textStatus, errorThrown){
            alert('Error deleting data');
          }
        });
      }
    }else{
      alert('no data selected');
  }
}
</script>