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
    <li class="">Ekspedisi</li>
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
          <a href="master/unit_ekspedisi">
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
            <form class="form-horizontal" action="master/unit_ekspedisi/save" method="post" enctype="multipart/form-data">
              <div class="box-body">   
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Unit Transporter</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="ID Unit Transporter" name="id_unit_transporter">
                  </div>               
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="No Polisi" name="no_polisi">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Merk" name="merk">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Warna" name="warna">
                  </div>
                </div>                                                                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Ekspedisi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_vendor">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_vendor->result() as $val) {
                        echo "
                        <option value='$val->id_vendor'>$val->vendor_name</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kapasitas</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Kapasitas" name="kapasitas">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kelengkapan</label>            
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="keur" value="1" checked>
                      KEUR
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="stnk" value="1" checked>
                      STNK
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="izin_operasi" value="1" checked>
                      Izin Operasi
                    </div>
                  </div>
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="tools" value="1" checked>
                      Tools
                    </div>
                  </div>
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="apar" value="1" checked>
                      Apar
                    </div>
                  </div>                                                        
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="segitiga" value="1" checked>
                      Segitiga Pengaman
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>     
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="kotak_p3k" value="1" checked>
                      Kotak P3K
                    </div>
                  </div>                                      
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="safety_belt" value="1" checked>
                      Safety Belt
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="flat-red" name="izin_trayek" value="1" checked>
                      Izin Trayek
                    </div>
                  </div>                  
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="No Izin Trayek" name="no_izin">                    
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
      $row = $dt_unit_transporter->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/unit_ekspedisi">
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
            <form class="form-horizontal" action="master/unit_ekspedisi/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_unit_transporter ?>" />
              <div class="box-body">      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Unit Transporter</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->id_unit_transporter ?>" required id="inputEmail3" placeholder="ID Unit Transporter" name="id_unit_transporter">
                  </div>               
                  <label for="inputEmail3" class="col-sm-2 control-label">No Polisi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->no_polisi ?>" required id="inputEmail3" placeholder="No Polisi" name="no_polisi">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Merk</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->merk ?>" required id="inputEmail3" placeholder="Merk" name="merk">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->warna ?>" required id="inputEmail3" placeholder="Warna" name="warna">
                  </div>
                </div>           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Ekspedisi</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_vendor">
                      <option value="<?php echo $row->id_vendor ?>">
                        <?php 
                        $r = $this->m_admin->getByID("ms_vendor","id_vendor",$row->id_vendor)->row();                  
                        echo $r->vendor_name;
                        ?>
                      </option>
                      <?php 
                      $dt_vendor  = $this->m_admin->kondisi("ms_vendor","id_vendor != '$row->id_vendor'");                                                                                     
                      foreach ($dt_vendor->result() as $val) {
                        echo "
                        <option value='$val->id_vendor'>$val->vendor_name</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kapasitas</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->kapasitas ?>" class="form-control" id="inputEmail3" placeholder="Kapasitas" name="kapasitas">
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kelengkapan</label>            
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->keur=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="keur" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="keur" value="1">
                      <?php } ?>
                      KEUR
                    </div>
                  </div>                  
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->stnk=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="stnk" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="stnk" value="1">
                      <?php } ?>
                      STNK
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->izin_operasi=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="izin_operasi" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="izin_operasi" value="1">
                      <?php } ?>
                      Izin Operasi
                    </div>
                  </div>
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->tools=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="tools" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="tools" value="1">
                      <?php } ?>
                      Tools
                    </div>
                  </div>
                  <div class="col-sm-1">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->apar=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="apar" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="apar" value="1">
                      <?php } ?>
                      Apar
                    </div>
                  </div>                                                        
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->segitiga=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="segitiga" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="segitiga" value="1">
                      <?php } ?>
                      Segitiga Pengaman
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label"></label>     
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->kotak_p3k=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="kotak_p3k" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="kotak_p3k" value="1">
                      <?php } ?>
                      Kotak P3K
                    </div>
                  </div>                                      
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->safety_belt=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="safety_belt" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="safety_belt" value="1">
                      <?php } ?>
                      Safety Belt
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->izin_trayek=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="izin_trayek" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="izin_trayek" value="1">
                      <?php } ?>
                      Izin Trayek
                    </div>
                  </div>                  
                  <div class="col-sm-4">                    
                    <input type="text" value="<?php echo $row->no_izin ?>" class="form-control" required id="inputEmail3" placeholder="No Izin Trayek" name="no_izin">                    
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
          <a href="master/unit_ekspedisi/add">
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
              <th>ID Unit Transporter</th>                            
              <th>No Polisi</th>                            
              <th>Merk</th>                            
              <th>Warna</th>
              <th>Ekspedisi</th>
              <th>Kapasitas</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_unit_transporter->result() as $row) {       
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->id_unit_transporter</td>              
              <td>$row->no_polisi</td>              
              <td>$row->merk</td>                            
              <td>$row->warna</td>                            
              <td>$row->vendor_name</td>                            
              <td>$row->kapasitas</td>                            
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/unit_ekspedisi/delete?id=<?php echo $row->id_unit_transporter ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/unit_ekspedisi/edit?id=<?php echo $row->id_unit_transporter ?>'><i class='fa fa-edit'></i></a>
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
          url: "<?php echo site_url('master/unit_ekspedisi/ajax_bulk_delete')?>",
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