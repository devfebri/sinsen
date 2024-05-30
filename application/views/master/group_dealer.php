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
    <li class="">Dealer</li>
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
          <a href="master/group_dealer">
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
            <form class="form-horizontal" action="master/group_dealer/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Group Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" autofocus required id="id_group_dealer" placeholder="ID Group Dealer" name="id_group_dealer">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Group Dealer</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control"  required id="inputEmail3" placeholder="Nama Group Dealer" name="group_dealer">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">QQ Kwitansi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control"  required id="inputEmail3" placeholder="QQ Kwitansi" name="qq_kwitansi">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>                  
                </div> 
                <hr>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Head Office</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <select id="head_office" class="form-control">
                        <option>Aktif</option>
                        <option>Tidak Aktif</option>
                      </select>
                    </div>
                  </div>                  
                </div>
                <div class="form-group">                          
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-8">
                   <button type="button" onClick="simpan_group()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                   <button type="button" onClick="kirim_data_group()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                   <button type="button" onClick="hide_group()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-10">
                    <div id="tampil_group"></div>
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
      $row = $dt_group_dealer->row(); 
    ?>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/group_dealer">
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
            <form class="form-horizontal" action="master/group_dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_group_dealer ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Group Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" disabled value="<?php echo $row->id_group_dealer ?>" class="form-control" autofocus required id="id_group_dealer" placeholder="ID Group Dealer" name="id_group_dealer">
                  </div>
                </div>                                                                          
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Group Dealer</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->group_dealer ?>"  required id="inputEmail3" placeholder="Nama Group Dealer" name="group_dealer">
                  </div>
                </div>   
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">QQ Kwitansi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->qq_kwitansi ?>"  required id="inputEmail3" placeholder="QQ Kwitansi" name="qq_kwitansi">
                  </div>
                </div>                
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
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
                <hr>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Head Office</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <select id="head_office" class="form-control">
                        <option>Aktif</option>
                        <option>Tidak Aktif</option>
                      </select>
                    </div>
                  </div>                  
                </div>
                <div class="form-group">                          
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-8">
                   <button type="button" onClick="simpan_group()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                   <button type="button" onClick="kirim_data_group()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                   <button type="button" onClick="hide_group()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-10">
                    <div id="tampil_group"></div>
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
    }elseif($set=="views"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/group_dealer/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>   
          
          <a href="master/group_dealer/custome">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Costume</button>
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
              <th>ID Group Dealer</th>                            
              <th>Group Dealer</th>                            
              <th>Nama Dealer</th>
              <th>QQ Kwitansi</th>
              <th width="5%">Active</th>                            
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_group_dealer->result() as $row) {          
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
            $sql = $this->db->query("SELECT * FROM ms_group_dealer_detail INNER JOIN ms_dealer ON ms_group_dealer_detail.id_dealer=ms_dealer.id_dealer WHERE id_group_dealer = '$row->id_group_dealer'");
          echo "          
            <tr>
              <td>$no</td>              
              <td>$row->id_group_dealer</td>                            
              <td>$row->group_dealer</td>                            
              <td>";
              foreach ($sql->result() as $k) {
                echo "$k->nama_dealer <br>";
              }
              echo "
              </td>
              <td>$row->qq_kwitansi</td>                            
              <td>$active</td>                            
              <td>";
              ?>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/group_dealer/delete?id=<?php echo $row->id_group_dealer ?>"><i class="fa fa-trash-o"></i></a>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/group_dealer/edit?id=<?php echo $row->id_group_dealer ?>'><i class='fa fa-edit'></i></a>
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

    elseif($set=="custome"){
      ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="master/group_dealer/custome_add">
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
                <th>Group Dealer</th>                            
                <th>QQ Kwitansi</th>
                <th>Dealer</th>
                <th>Status</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>            
            <?php 
            $no=1; 
            foreach($dt_group_dealer->result() as $row) {?>
                  <tr>
                    <td><?=$no++?></td>
                    <td>
                       <a href="/master/group_dealer/custome?id=<?=$row->id_group_dealer_custome?>"><?=$row->id_group_dealer_custome?></a>  
                    </td>
                    <td><?=$row->qq_kwitansi?></td>
                    <td><?php 
                      $id_get = $this->db->query("SELECT md.nama_dealer FROM ms_group_dealer_custome_detail gdn LEFT JOIN ms_dealer md ON gdn.id_dealer = md.id_dealer WHERE gdn.id_group_dealer_custome = '".$row->id_group_dealer_custome."' GROUP BY gdn.id_dealer")->result();
                      foreach ($id_get as $dealer) {
                          echo $dealer->nama_dealer .'<br>';   
                      }
                  ?></td>
                    <td><?if ($row->active == 1)
                    echo '<i class="glyphicon glyphicon-ok"></i>';
                    ?></td>
                    <td>
                      <a data-toggle="tooltip" title="" class="btn btn-primary btn-sm btn-flat" href="master/group_dealer/edit_group?id=<?=$row->id_group_dealer_custome?>" data-original-title="Edit Data"><i class="fa fa-edit"></i></a>
                      <a data-toggle="tooltip" title="" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/group_dealer/delete_group_customer?id=<?=$row->id_group_dealer_custome?>" data-original-title="Delete Data"><i class="fa fa-trash-o"></i></a>
                    </td>
                  </tr>     
            <?}
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
function simpan_group_costume() {
    var selectedDealer = $('#id_dealer_custome option:selected').data('set-dealer');
    var id_dealer = $("#id_dealer_custome").val();

    var newID = $("<input>").attr({
        type: 'hidden',
        name: 'id_dealer_temp[]',
        class: 'form-control',
        value: id_dealer,
        id: 'id_dealer' + id_dealer,
        readonly: true
    });

    var newName = $("<input>").attr({
        type: 'text',
        class: 'form-control',
        value: selectedDealer,
        readonly: true
    });

    var newParagraph = $("<p>");
    newParagraph.append(newID);
    newParagraph.append(newName);
    $("#tampil_group").append(newParagraph);
}
</script>


<script type="text/javascript">
function hide_group(){
    $("#tampil_group").hide();
}
function kirim_data_group(){    
  $("#tampil_group").show();
  var id_group_dealer = document.getElementById("id_group_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_group_dealer="+id_group_dealer;                           
     xhr.open("POST", "master/group_dealer/t_group", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_group").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}

function simpan_group(){
    var id_group_dealer   = document.getElementById("id_group_dealer").value;   
    var head_office       = document.getElementById("head_office").value;       
    var id_dealer         = $("#id_dealer").val();            
    //alert(active);
    if (id_group_dealer=="" || id_dealer=="") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/group_dealer/save_group')?>",
            type:"POST",
            data:"id_group_dealer="+id_group_dealer+"&id_dealer="+id_dealer+"&head_office="+head_office,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_group();
                    kosong();                
                }else{
                    alert('Dealer ini sudah ditambahkan');
                    kosong();                      
                }                
            }
        })    
    }
}


function kosong(args){
  $("#id_dealer").val("");     
}
function hapus_group(a,b){ 
    var id_group_dealer_detail  = a;   
    var id_group_dealer   = b;       
    $.ajax({
        url : "<?php echo site_url('master/group_dealer/delete_group')?>",
        type:"POST",
        data:"id_group_dealer_detail="+id_group_dealer_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_group();
            }
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
          url: "<?php echo site_url('master/group_dealer/ajax_bulk_delete')?>",
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
</>