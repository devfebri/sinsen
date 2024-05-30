<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<body onload="auto()">
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Master Data</li>
    <li class="">Apparel & Item Promosi</li>
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
          <a href="master/item_promosi">
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
            <form class="form-horizontal" action="master/item_promosi/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Item Promosi</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="tgl" value="<?php echo date("y-m-d") ?>">
                    <input type="text" class="form-control" required id="id_item_promosi" readonly placeholder="ID Item Promosi" name="id_item_promosi">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Item Promosi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Item Promosi" name="item_promosi">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kategori Item</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="id_kategori_item">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_kategori_item->result() as $val) {
                        echo "
                        <option value='$val->id_kategori_item'>$val->kategori_item</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Satuan</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="id_satuan">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_satuan->result() as $val) {
                        echo "
                        <option value='$val->id_satuan'>$val->satuan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Jual</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeypress="return number_only(event)" required id="inputEmail3" placeholder="Harga Jual" name="harga_jual">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Beli</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" onkeypress="return number_only(event)" required id="inputEmail3" placeholder="Harga Beli" name="harga_beli">
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
      $row = $dt_item_promosi->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/item_promosi">
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
            <form class="form-horizontal" action="master/item_promosi/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_item_promosi ?>" />
              <div class="box-body">    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Item Promosi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->id_item_promosi ?>" autofocus required id="id_item_promosi" readonly placeholder="ID Item Promosi" name="id_item_promosi">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Item Promosi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->item_promosi ?>" required id="inputEmail3" placeholder="Item Promosi" name="item_promosi">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kategori Item</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="id_kategori_item">
                     <option value="<?php echo $row->id_kategori_item ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kategori_item","id_kategori_item",$row->id_kategori_item)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kategori_item;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_kategori_item = $this->m_admin->kondisi("ms_kategori_item","id_kategori_item != '$row->id_kategori_item'");                                                
                      foreach($dt_kategori_item->result() as $val) {
                        echo "
                        <option value='$val->id_kategori_item'>$val->kategori_item</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Satuan</label>
                  <div class="col-sm-4">
                    <select class="form-control" required name="id_satuan">
                      <option value="<?php echo $row->id_satuan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_satuan","id_satuan",$row->id_satuan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->satuan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_satuan = $this->m_admin->kondisi("ms_satuan","id_satuan != ".$row->id_satuan);                                                
                      foreach($dt_satuan->result() as $val) {
                        echo "
                        <option value='$val->id_satuan'>$val->satuan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Jual</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->harga_jual ?>" onkeypress="return number_only(event)" required id="inputEmail3" placeholder="Harga Jual" name="harga_jual">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Beli</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->harga_beli ?>" onkeypress="return number_only(event)" required id="inputEmail3" placeholder="Harga Beli" name="harga_beli">
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
          <a href="master/item_promosi/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th width="5%">No</th>              
              <th>ID Item Promosi</th>              
              <th>Item Promosi</th>              
              <th>Kategori Item</th>
              <th>Satuan</th>
              <th>Harga Jual</th>
              <th>Harga Beli</th>
              <th width="5%">Active</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_item_promosi->result() as $row) { 
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";                  
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->id_item_promosi</td>                            
              <td>$row->item_promosi</td>                            
              <td>$row->kategori_item</td>                            
              <td>$row->satuan</td>                            
              <td>$row->harga_jual</td>                            
              <td>$row->harga_beli</td>                            
              <td>$active</td>              
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/item_promosi/delete?id=<?php echo $row->id_item_promosi ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/item_promosi/edit?id=<?php echo $row->id_item_promosi ?>'><i class='fa fa-edit'></i></a>
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
function auto(){
  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('master/item_promosi/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_item_promosi").val(data[0]);                               
      }        
  })
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
          url: "<?php echo site_url('master/item_promosi/ajax_bulk_delete')?>",
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