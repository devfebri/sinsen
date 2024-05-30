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
          <a href="master/master_lead">
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
            <form class="form-horizontal" action="master/master_lead/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" required>
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Lead Time AHM ke MD (hari)</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="0" class="form-control" onchange="total_lead()" placeholder="Lead Time AHM ke MD" id="lead_time_ahm_md" name="lead_time_ahm_md" required>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Proses Receiving & Admin di MD (hari)</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="0" class="form-control" onchange="total_lead()" placeholder="Proses Receiving & Admin di MD" name="proses_receiving_md" id="proses_receiving_md" required> 
                  </div>                                                               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lead Time MD ke Dealer (hari)</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="0" class="form-control" onchange="total_lead()"  placeholder="Lead Time MD ke Dealer" name="lead_time_md_d" id="lead_time_md_d" required>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Proses Receiving & Admin di Dealer (hari)</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="0" class="form-control" onchange="total_lead()" placeholder="Proses Receiving & Admin di Dealer" name="proses_receiving" id="proses_receiving" required>
                  </div>                                  
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Total Lead Time (hari)</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="total_lead_time" id="total_lead_time" value="0" readonly placeholder="Total Lead Time" required>                                        
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status" required>                      
                      <option>Aktif</option>
                      <option>Non Aktif</option>
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
      $row = $dt_master_lead->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/master_lead">
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
            <form class="form-horizontal" action="master/master_lead/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_master_lead_detail ?>" />
              <div class="box-body">                                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer" required>
                      <option value="<?php echo $row->id_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo "$dt_cust->nama_dealer";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
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
                  <label for="inputEmail3" class="col-sm-2 control-label">Lead Time AHM ke MD (hari)</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="<?php echo $row->lead_time_ahm_md ?>" class="form-control" onchange="total_lead()" placeholder="Lead Time AHM ke MD" name="lead_time_ahm_md" id="lead_time_ahm_md" required>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Proses Receiving & Admin di MD (hari)</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="<?php echo $row->proses_receiving_md ?>" class="form-control" onchange="total_lead()" placeholder="Proses Receiving & Admin di MD" name="proses_receiving_md" id="proses_receiving_md" required>
                  </div>                                                               
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lead Time MD ke Dealer (hari)</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="<?php echo $row->lead_time_md_d ?>" class="form-control" onchange="total_lead()"  placeholder="Lead Time MD ke Dealer" name="lead_time_md_d" id="lead_time_md_d" required>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Proses Receiving & Admin di Dealer (hari)</label>
                  <div class="col-sm-4">
                    <input type="text" onkeypress="return number_only(event)" value="<?php echo $row->proses_receiving ?>" class="form-control" onchange="total_lead()" placeholder="Proses Receiving & Admin di Dealer" name="proses_receiving" id="proses_receiving" required>
                  </div>                                  
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Total Lead Time (hari)</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" name="total_lead_time" id="total_lead_time" value="<?php echo $row->total_lead_time ?>" readonly placeholder="Total Lead Time" required>                                        
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status" required>                      
                      <option>Aktif</option>
                      <option>Non Aktif</option>
                    </select>
                  </div>                                  
                </div>                
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
          <a href="master/master_lead/add">
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
              <th>Dealer</th>
              <th>Lead Time AHM to MD</th>
              <th>Proses Receiving MD</th>
              <th>Lead Time MD to D</th>
              <th>Proses Receiving D</th>
              <th>Total Lead Time</th>
              <th width="5%">Active</th>                            
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_master_lead->result() as $k) {          
            if($k->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";                                      
            echo "
            <tr>
              <td>$no</td>                                                                
              <td>$k->nama_dealer</td>                                          
              <td>$k->lead_time_ahm_md</td>                                          
              <td>$k->proses_receiving_md</td>                                          
              <td>$k->lead_time_md_d</td>                                          
              <td>$k->proses_receiving</td>                                          
              <td>$k->total_lead_time</td>
              <td>$active</td>
              <td>"; ?>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/master_lead/delete?id=<?php echo $k->id_master_lead_detail ?>"><i class="fa fa-trash-o"></i></a>
                <a <?php echo $this->m_admin->set_tombol($id_menu,$group,"edit"); ?> data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/master_lead/edit?id=<?php echo $k->id_master_lead_detail ?>'><i class='fa fa-edit'></i></a>
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
function hide_master(){
    $("#tampil_master").hide();
}
function kirim_data_master(){    
  $("#tampil_master").show();
  var id_master_lead = document.getElementById("id_master_lead").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_master_lead="+id_master_lead;                           
     xhr.open("POST", "master/master_lead/t_master", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_master").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function total_lead(){
  var a1      = document.getElementById("lead_time_ahm_md").value;       
  var a2      = document.getElementById("proses_receiving_md").value;
  var a3      = document.getElementById("lead_time_md_d").value;       
  var a4      = document.getElementById("proses_receiving").value;       
  total = parseInt(a1) + parseInt(a2) + parseInt(a3) + parseInt(a4);
  document.getElementById("total_lead_time").value = total;
  //alert(a1);
}
function tes(){
  alert("hello");
}
function simpan_master(){
    var id_master_lead    = document.getElementById("id_master_lead").value;   
    var id_dealer         = document.getElementById("id_dealer").value;       
    var lead_time_ahm_md    = document.getElementById("lead_time_ahm_md").value;       
    var proses_receiving_md  = document.getElementById("proses_receiving_md").value;
    var lead_time_md_d    = document.getElementById("lead_time_md_d").value;       
    var proses_receiving  = document.getElementById("proses_receiving").value;       
    var total_lead_time   = $("#total_lead_time").val();            
    var status            = $("#status").val();            
    //alert(active);
    if (id_master_lead=="" || id_dealer=="") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('master/master_lead/save_master')?>",
            type:"POST",
            data:"id_master_lead="+id_master_lead+"&id_dealer="+id_dealer+"&lead_time_ahm_md="+lead_time_ahm_md+"&proses_receiving_md="+proses_receiving_md+"&lead_time_md_d="+lead_time_md_d+"&proses_receiving="+proses_receiving+"&total_lead_time="+total_lead_time+"&status="+status,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_master();
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
  $("#total_lead_time").val("0");     
  $("#lead_time_md_d").val("0");     
  $("#lead_time_ahm_md").val("0");     
  $("#status").val("Aktif");     
  $("#proses_receiving").val("0");     
  $("#proses_receiving_md").val("0");     
}
function hapus_master(a,b){ 
    var id_master_lead_detail  = a;   
    var id_master_lead   = b;       
    $.ajax({
        url : "<?php echo site_url('master/master_lead/delete_master')?>",
        type:"POST",
        data:"id_master_lead_detail="+id_master_lead_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_master();
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
          url: "<?php echo site_url('master/master_lead/ajax_bulk_delete')?>",
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