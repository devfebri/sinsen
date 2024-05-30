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
    <li class="">Setting</li>
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
          <a href="master/setting_dealer">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/setting_dealer/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Perusahaan</label>           
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="Nama Perusahaan" name="nama_perusahaan" required>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Nama Kecil Perusahaan</label>           
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="Nama Kecil Perusahaan" name="nama_kecil" required>
                  </div>                                                
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Alamat</label>            
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="field-1" placeholder="Alamat" name="alamat">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Nama Pimpinan</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="Nama Pimpinan" name="nama_pimpinan">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">ID Dealer</label>            
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->kode_dealer_md | $val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>                            
                <div class="form-group">                              
                  <label for="field-1" class="col-sm-2 control-label">No Telp</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="No Telp" name="no_telp">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Email</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="field-1" placeholder="Email" name="email">
                  </div>
                  </div>
                </div>    
                <div class="form-group">                              
                  <label for="field-1" class="col-sm-2 control-label">Logo</label>            
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="logo">
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Favicon</label>            
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="favicon">
                  </div>
                  </div>
                </div>                    
              </div>
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
      $row = $dt_setting_dealer->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/setting_dealer">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="master/setting_dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_user ?>" />
              <div class="box-body">                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kelurahan</label>           
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_kelurahan" autofocus>
                      <option value="<?php echo $row->id_kelurahan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelurahan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_kelurahan = $this->m_admin->kondisi("ms_kelurahan","id_kelurahan != ".$row->id_kelurahan);                                                
                      foreach($dt_kelurahan->result() as $val) {
                        echo "
                        <option value='$val->id_kelurahan'>$val->kelurahan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Karyawan</label>           
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_karyawan_dealer">
                      <option value="<?php echo $row->id_karyawan_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_karyawan_dealer","id_karyawan_dealer",$row->id_karyawan_dealer)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->id_flp_md." | ".$dt_cust->nama_lengkap;
                        }else{
                          echo "- choose -";
                        }                        
                        ?>
                      </option>
                      <?php 
                      $dt_karyawan = $this->m_admin->kondisiCond("ms_karyawan_dealer","id_karyawan_dealer != ".$row->id_karyawan_dealer);                                                
                      foreach($dt_karyawan->result() as $val) {
                        echo "
                        <option value='$val->id_karyawan'>$val->id_flp_md | $val->nama_lengkap</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                                                
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Username</label>            
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->username ?>" class="form-control" id="field-1" placeholder="Username" name="username" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Password</label>            
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="field-1" placeholder="Kosongkan jika tidak diubah" name="password">
                  </div>
                </div>                            
                <div class="form-group">                              
                  <label for="field-1" class="col-sm-2 control-label">Admin Password</label>            
                  <div class="col-sm-4">
                    <input type="password" class="form-control" id="field-1" placeholder="Kosongkan jika tidak diubah" name="admin_password">
                  </div>
                

                  <label for="field-1" class="col-sm-2 control-label">User Group</label>            
                  <div class="col-sm-4">
                    <select class="form-control" name="id_user_group" onchange="cek()" id="id_user_group">
                      <option value="<?php echo $row->id_user_group ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_user_group","id_user_group",$row->id_user_group)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->user_group;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_user_group = $this->m_admin->kondisi("ms_user_group","id_user_group != ".$row->id_user_group);                                                
                      foreach($dt_user_group->result() as $val) {
                        echo "
                        <option value='$val->id_user_group'>$val->user_group</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-4">
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
                  <label for="field-1" class="col-sm-2 control-label">Avatar</label>            
                  <div class="col-sm-3">              
                    <input type="file" name="avatar">             
                  </div>
                  <div class="col-sm-2">            
                    <a href="#modal_foto" class="btn btn-primary" data-toggle="modal">
                        Show</button>              
                    </a>
                    
                  </div>
                </div>
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="button" onclick="cek()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_user->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/user">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="master/user/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_user ?>" />
              <div class="box-body">                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kelurahan</label>           
                  <div class="col-sm-4">
                    <select disabled class="form-control select2" name="id_kelurahan" autofocus>
                      <option value="<?php echo $row->id_kelurahan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_kelurahan","id_kelurahan",$row->id_kelurahan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->kelurahan;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_kelurahan = $this->m_admin->kondisi("ms_kelurahan","id_kelurahan != ".$row->id_kelurahan);                                                
                      foreach($dt_kelurahan->result() as $val) {
                        echo "
                        <option value='$val->id_kelurahan'>$val->kelurahan</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Karyawan</label>           
                  <div class="col-sm-4">
                    <select disabled class="form-control select2" name="id_karyawan_dealer">
                      <option value="<?php echo $row->id_karyawan_dealer ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_karyawan_dealer","id_karyawan_dealer",$row->id_karyawan_dealer)->row();                                                         
                        if(isset($dt_cust)){
                          echo $dt_cust->id_flp_md." | ".$dt_cust->nama_lengkap;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_karyawan = $this->m_admin->kondisiCond("ms_karyawan_dealer","id_karyawan_dealer != ".$row->id_karyawan_dealer);                                                
                      foreach($dt_karyawan->result() as $val) {
                        echo "
                        <option value='$val->id_karyawan_dealer'>$val->id_flp_md | $val->nama_lengkap</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                                                
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Username</label>            
                  <div class="col-sm-10">
                    <input disabled type="text" value="<?php echo $row->username ?>" class="form-control" id="field-1" placeholder="Username" name="username" required>
                  </div>
                </div>                
                <div class="form-group">                              
                  <label for="field-1" class="col-sm-2 control-label">Admin Password</label>            
                  <div class="col-sm-4">
                    <input disabled type="text" value="<?php echo $row->admin_password ?>" class="form-control" id="field-1" placeholder="Kosongkan jika tidak diubah" name="admin_password">
                  </div>
                
                  <label for="field-1" class="col-sm-2 control-label">User Group</label>            
                  <div class="col-sm-4">
                    <select disabled class="form-control" name="id_user_group" onchange="cek()" id="id_user_group">
                      <option value="<?php echo $row->id_user_group ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_user_group","id_user_group",$row->id_user_group)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->user_group;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_user_group = $this->m_admin->kondisi("ms_user_group","id_user_group != ".$row->id_user_group);                                                
                      foreach($dt_user_group->result() as $val) {
                        echo "
                        <option value='$val->id_user_group'>$val->user_group</option>;
                        ";
                      }
                      ?>
                    </select>                                  
                  </div>            
                </div>    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-4">
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
                  <label for="field-1" class="col-sm-2 control-label">Avatar</label>            
                  
                  <div class="col-sm-2">            
                    <a href="#modal_foto" class="btn btn-primary" data-toggle="modal">
                        Show</button>              
                    </a>
                    
                  </div>
                </div>
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <a href="master/user/edit?id=<?php echo $row->id_user ?>">
                    <button type="button" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Edit Data</button>                
                  </a>
                  <button type="button" onclick="cek()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                
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
          <a href="master/setting_dealer/add">
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
              <th>Dealer</th>             
              <th>Nama Perusahaan</th>
              <th>Nama Kecil</th>              
              <th>Pimpinan</th>
              <th>Alamat</th>
              <th>No Telp</th>
              <th>Email</th>          
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_setting->result() as $val) {                          
              echo"
              <tr>
                <td>$no</td>
                <td>$val->nama_dealer</td>
                <td>$val->nama_perusahaan</td>               
                <td>$val->nama_kecil</td>
                <td>$val->nama_pimpinan</td>                                                          
                <td>$val->alamat</td>               
                <td>$val->no_telp</td>               
                <td>$val->email</td>                               
                <td>"; ?>
                  <a href="master/setting_dealer/delete?id=<?php echo $val->id_setting ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                  <a href="master/setting_dealer/edit?id=<?php echo $val->id_setting ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>                
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

<div class="modal fade" id="modal_foto">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <center>
          <img src="assets/panel/images/user/<?php echo $row->avatar ?>" width='100%'>
        </center>
      </div>      
    </div>
  </div>
</div>


<script type="text/javascript">
// $("#id_user_group").change(function(){
//   //var id_user_group = $("#id_user_group").val();  
//   alert("s"); 
// });
</script>
<script type="text/javascript">
function cek(){
  var id_user_group = $("#id_user_group").val();  
  $.ajax({
    url : "<?php echo site_url('master/user/get_user_group')?>",
    type:"POST",
    data:"id_user_group="+id_user_group,      
    cache:false,   
    success:function(msg){            
      $("#id_user_level").html(msg);         
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
          url: "<?php echo site_url('master/user/ajax_bulk_delete')?>",
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