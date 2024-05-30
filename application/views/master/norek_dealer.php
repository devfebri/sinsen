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
          <a href="master/norek_dealer">
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
            <form class="form-horizontal" action="master/norek_dealer/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Norek Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" autofocus required id="id_norek_dealer" placeholder="ID Norek Dealer" name="id_norek_dealer">
                  </div>
                                             
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_dealer">
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="id_bank">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_bank->result() as $val) {
                        echo "
                        <option value='$val->id_bank'>$val->bank</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Jenis Rekening</label>            
                  <div class="col-sm-4">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <select id="jenis_rek" class="form-control">
                        <option value="">- choose -</option>
                        <option>Tabungan</option>
                        <option>Giro</option>
                      </select>
                    </div>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Rekening</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_rek" placeholder="No.Rekening">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Nama Rekening</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_rek" placeholder="Nama Rekening">                                        
                  </div>                  
                </div>
                <div class="form-group">                          
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-8">
                   <button type="button" onClick="simpan_norek()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                   <button type="button" onClick="kirim_data_norek()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                   <button type="button" onClick="hide_norek()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-10">
                    <div id="tampil_norek"></div>
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
      $row = $dt_norek_dealer->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/norek_dealer">
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
            <form class="form-horizontal" action="master/norek_dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_norek_dealer ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Norek Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $row->id_norek_dealer ?>" autofocus required id="id_norek_dealer" placeholder="ID Norek Dealer" name="id_norek_dealer">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_dealer">
                      <option value="<?php echo $row->id_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->nama_dealer;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_dealer = $this->m_admin->kondisiCond("ms_dealer","id_dealer != ".$row->id_dealer);                                                
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="id_bank">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_bank->result() as $val) {
                        echo "
                        <option value='$val->id_bank'>$val->bank</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Jenis Rekening</label>            
                  <div class="col-sm-4">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <select id="jenis_rek" class="form-control">
                        <option value="">- choose -</option>
                        <option>Tabungan</option>
                        <option>Giro</option>
                      </select>
                    </div>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.Rekening</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="no_rek" placeholder="No.Rekening">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Nama Rekening</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="nama_rek" placeholder="Nama Rekening">                                        
                  </div>                  
                </div>
                <div class="form-group">                          
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-8">
                   <button type="button" onClick="simpan_norek()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                   <button type="button" onClick="kirim_data_norek()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                   <button type="button" onClick="hide_norek()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                  </div> 
                </div>
                <div class="form-group">
                  <div class="col-sm-2">
                  </div>  
                  <div class="col-sm-10">
                    <div id="tampil_norek"></div>
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
          <a href="master/norek_dealer/add">
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>              
              <th>ID Rek Dealer</th>                            
              <th>Dealer</th>                                          
              <th>Bank - Jenis - No.Rek - Nama.Rek</th>
              <th width="5%">Active</th>                            
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_norek_dealer->result() as $row) {          
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";            
            $sql = $this->db->query("SELECT * FROM ms_norek_dealer_detail INNER JOIN ms_bank 
                ON ms_norek_dealer_detail.id_bank=ms_bank.id_bank WHERE id_norek_dealer = '$row->id_norek_dealer'");
            $isi = 1;
          echo "          
            <tr>
              <td>$no</td>              
              <td>$row->id_norek_dealer</td>                            
              <td>$row->nama_dealer</td>                                          
              <td>";
              foreach ($sql->result() as $k) {
                echo "$isi. $k->bank - $k->jenis_rek - $k->no_rek - $k->nama_rek <br>";
                $isi++;
              }
              echo "
              </td>                            
              <td>$active</td>                            
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/norek_dealer/delete?id=<?php echo $row->id_norek_dealer ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/norek_dealer/edit?id=<?php echo $row->id_norek_dealer ?>'><i class='fa fa-edit'></i></a>
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
function hide_norek(){
    $("#tampil_norek").hide();
}
function kirim_data_norek(){    
  $("#tampil_norek").show();
  var id_norek_dealer = document.getElementById("id_norek_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_norek_dealer="+id_norek_dealer;                           
     xhr.open("POST", "master/norek_dealer/t_norek", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_norek").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_norek(){
    var id_norek_dealer   = document.getElementById("id_norek_dealer").value;   
    var jenis_rek         = document.getElementById("jenis_rek").value;       
    var nama_rek          = document.getElementById("nama_rek").value;       
    var id_bank           = document.getElementById("id_bank").value;       
    var no_rek            = $("#no_rek").val();            
    //alert(active);
    if (id_norek_dealer=="" || id_bank=="") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/norek_dealer/save_norek')?>",
            type:"POST",
            data:"id_norek_dealer="+id_norek_dealer+"&id_bank="+id_bank+"&nama_rek="+nama_rek+"&no_rek="+no_rek+"&jenis_rek="+jenis_rek,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_norek();
                    kosong();                
                }else{
                    alert('Bank ini sudah ditambahkan');
                    kosong();                      
                }                
            }
        })    
    }
}
function kosong(args){
  $("#id_bank").val("");     
  $("#jenis_rek").val("");     
  $("#no_rek").val("");     
  $("#nama_rek").val("");     
}
function hapus_norek(a,b){ 
    var id_norek_dealer_detail  = a;   
    var id_norek_dealer   = b;       
    $.ajax({
        url : "<?php echo site_url('master/norek_dealer/delete_norek')?>",
        type:"POST",
        data:"id_norek_dealer_detail="+id_norek_dealer_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_norek();
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
          url: "<?php echo site_url('master/norek_dealer/ajax_bulk_delete')?>",
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