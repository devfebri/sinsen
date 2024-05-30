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
    <li class="">Vendor</li>
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
          <a href="master/vendor">
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
            <form class="form-horizontal" action="master/vendor/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" autofocus required id="inputEmail3" placeholder="ID Vendor" name="id_vendor">
                  </div>
                </div>                                                                          
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Vendor Name</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Vendor Name" name="vendor_name">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Alias</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Alias" name="alias">
                  </div>
                </div>                 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Telp</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="No.Telp" name="no_telp">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Rekening</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="No.Rekening" name="no_rekening">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Rekening</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Nama Rekening" name="nama_rekening">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">PPN</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="PPN" name="ppn">
                  </div>
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Atas Nama Bank</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Atas Nama Bank" name="atas_nama_bank">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Alamat" name="alamat">
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Vendor Type *</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_vendor_type" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_vendor_type->result() as $val) {
                        echo "
                        <option value='$val->id_vendor_type'>$val->vendor_type</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">PPh</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pph">
                      <option value="">- choose -</option>
                      <option>2</option>
                      <option>3</option>
                      <option>10</option>
                      <option>90</option>
                    </select>
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
      $row = $dt_vendor->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/vendor">
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
            <form class="form-horizontal" action="master/vendor/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_vendor ?>" />
              <div class="box-body">                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Vendor</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->id_vendor ?>" class="form-control" autofocus required id="inputEmail3" placeholder="ID Vendor" name="id_vendor">
                  </div>
                </div>                                                                          
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Vendor Name</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->vendor_name ?>" class="form-control" required id="inputEmail3" placeholder="Vendor Name" name="vendor_name">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Alias</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->alias ?>" class="form-control" required id="inputEmail3" placeholder="Alias" name="alias">
                  </div>
                </div>                 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Telp</label>
                  <div class="col-sm-4">
                    <input type="number" value="<?php echo $row->no_telp ?>" class="form-control" id="inputEmail3" placeholder="No.Telp" name="no_telp">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Rekening</label>
                  <div class="col-sm-4">
                    <input type="number" value="<?php echo $row->no_rekening ?>" class="form-control" id="inputEmail3" placeholder="No.Rekening" name="no_rekening">
                  </div>
                </div>    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Rekening</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->nama_rekening ?>" id="inputEmail3" placeholder="Nama Rekening" name="nama_rekening">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">PPN</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->ppn ?>" onkeypress="return number_only(event)" class="form-control" id="inputEmail3" placeholder="PPN" name="ppn">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Atas Nama Bank</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->atas_nama_bank ?>" id="inputEmail3" placeholder="Atas Nama Bank" name="atas_nama_bank">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->alamat ?>" class="form-control" id="inputEmail3" placeholder="Alamat" name="alamat">
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Vendor Type</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_vendor_type" required>
                      <option value="<?php echo $row->id_vendor_type ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_vendor_type","id_vendor_type",$row->id_vendor_type)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->vendor_type;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_vendor_type = $this->m_admin->kondisiCond("ms_vendor_type","id_vendor_type != '$row->id_vendor_type'");                                                
                      foreach($dt_vendor_type->result() as $val) {
                        echo "
                        <option value='$val->id_vendor_type'>$val->vendor_type</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>     
                  <label for="inputEmail3" class="col-sm-2 control-label">PPh</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pph">                                          
                      <option <?php if($row->pph=='') echo "selected"; ?> value="">- choose -</option>
                      <option <?php if($row->pph=='2') echo "selected"; ?>>2</option>
                      <option <?php if($row->pph=='3') echo "selected"; ?>>3</option>
                      <option <?php if($row->pph=='10') echo "selected"; ?>>10</option>
                      <option <?php if($row->pph=='90') echo "selected"; ?>>90</option>
                    </select>
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
          <a href="master/vendor/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>ID Vendor</th>
              <th>Name</th>
              <th>No.Telp</th>
              <th>Alamat</th>
              <th>Type</th>
              <th>PPN</th>
              <th>PPh</th>
              <th>No.Rekening</th>
              <th>Nama Rekening</th>
              <th>Atas Nama Bank</th>
              <th>Active</th>           
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_vendor->result() as $row) {       
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->id_vendor</td>              
              <td>$row->vendor_name</td>
              <td>$row->no_telp</td>              
              <td>$row->alamat</td>
              <td>$row->vendor_type</td>
              <td>$row->ppn</td>
              <td>$row->pph</td>
              <td>$row->no_rekening</td>
              <td>$row->nama_rekening</td>
              <td>$row->atas_nama_bank</td>
              <td>$active</td>
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/vendor/delete?id=<?php echo $row->id_vendor ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/vendor/edit?id=<?php echo $row->id_vendor ?>'><i class='fa fa-edit'></i></a>
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
          url: "<?php echo site_url('master/vendor/ajax_bulk_delete')?>",
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