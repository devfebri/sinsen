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
          <a href="master/rek_md">
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
            <form class="form-horizontal" action="master/rek_md/save" method="post" enctype="multipart/form-data" id="form_rek">
              <div class="box-body">
                <div class="form-group">                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bank" required>
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_bank");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->bank'>$isi->bank</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rekening</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="No Rekening" name="no_rekening" id='no_rekening' required>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Atas Nama</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Atas Nama" name="atas_nama" required>
                  </div>
                </div>                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode COA</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="kode_coa">
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_coa");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->kode_coa'>$isi->kode_coa</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Rekening</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_rek">
                      <option value="">- choose -</option>                    
                      <option>Unit</option>
                      <option>Part/Oil</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Keterangan" name="keterangan">
                  </div>                  
                </div>          
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div>                                                                                            
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="button" onclick="submitForm()" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
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
      $row = $dt_rek_md->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/rek_md">
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
            <form class="form-horizontal" action="master/rek_md/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" id="id_rek_md" value="<?php echo $row->id_rek_md ?>">
               <div class="box-body">
                <div class="form-group">                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bank" required>
                      <option><?php echo $row->bank ?></option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_bank");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->bank'>$isi->bank</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rekening</label>
                  <div class="col-sm-4">
                    <input value="<?php echo $row->no_rekening ?>" required type="text" class="form-control" id="no_rekening" placeholder="No Rekening" name="no_rekening" readonly>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Atas Nama</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->atas_nama ?>" required class="form-control" id="inputEmail3" placeholder="Atas Nama" name="atas_nama">
                  </div>
                </div>                                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode COA</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="kode_coa">
                      <option><?php echo $row->kode_coa ?></option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_coa");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->kode_coa'>$isi->kode_coa</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Rekening</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_rek">
                      <option><?php echo $row->jenis_rek ?></option>                    
                      <option>Unit</option>
                      <option>Part/Oil</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->keterangan ?>" class="form-control" id="inputEmail3" placeholder="Keterangan" name="keterangan">
                  </div>                  
                </div> 
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">
                      <?php } ?>
                      Active
                    </div>
                  </div>                  
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
          <a href="master/rek_md/add">
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
              <th>Bank</th>
              <th>No Rekening</th>                            
              <th>Atas Nama</th>
              <th>Kode COA</th>
              <th>Jenis Rekening</th>
              <th>Keterangan</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_rek_md->result() as $row) {                 
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->bank</td>              
              <td>$row->no_rekening</td>                        
              <td>$row->atas_nama</td>                        
              <td>$row->kode_coa</td>                                      
              <td>$row->jenis_rek</td>                                      
              <td>$row->keterangan</td>                                      
              <td>$active</td>              
              <td>";
              ?>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/rek_md/delete?id=<?php echo $row->id_rek_md ?>"><i class="fa fa-trash-o"></i></a>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/rek_md/edit?id=<?php echo $row->id_rek_md ?>'><i class='fa fa-edit'></i></a>
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
  function submitForm()
{
  value = {
    no_rekening:$('#no_rekening').val(),
  }
  $.ajax({
       url:"<?php echo site_url('master/rek_md/cekRekening');?>",
       type:"POST",
       data:value,
       cache:false,
       dataType:'JSON',
       success:function(response){
        if (response.status=='kosong') {
          $("#form_rek").submit();
        }else{
          alert(response.alert);
        }
       },
       statusCode: {
    500: function() {
     // $('#loading-status').hide();
      swal('Something Went Wrong');
    }
  }
  });
}
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
          url: "<?php echo site_url('master/rek_md/ajax_bulk_delete')?>",
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