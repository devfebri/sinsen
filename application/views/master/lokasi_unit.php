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
    <li class="">Lokasi</li>
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
          <a href="master/lokasi_unit">
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
            <form class="form-horizontal" action="master/lokasi_unit/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                                                                                                    
               <?php /* <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Lokasi Unit</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required autofocus id="inputEmail3" placeholder="ID Lokasi Unit" name="id_lokasi_unit">
                  </div>
                </div> */?>

                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lantai</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Lantai" name="lantai">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="id_gudang">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_gudang->result() as $val) {
                        echo "
                        <option value='$val->id_gudang'>$val->gudang</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>   
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kolom</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Kolom" name="kolom">
                  </div>
                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Qty</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Qty" name="qty">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Baris</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="inputEmail3" placeholder="Baris" name="baris">
                  </div>
                                 
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Dedicated</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="tipe_dedicated">
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan - $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Unit</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="status_unit">
                      <option value="">- choose -</option>
                      <option>RFS</option>
                      <option>NRFS</option>
                      
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
      $row = $dt_lokasi_unit->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/lokasi_unit">
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
            <form class="form-horizontal" action="master/lokasi_unit/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_lokasi_unit ?>" />
              <div class="box-body">    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Lokasi Unit</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?php echo $row->id_lokasi_unit ?>" required autofocus id="inputEmail3" placeholder="ID Lokasi Unit" name="id_lokasi_unit">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lantai</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?php echo $row->lantai ?>" required id="inputEmail3" placeholder="Lantai" name="lantai">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="id_gudang">
                      <option value="<?php echo $row->id_gudang ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_gudang","id_gudang",$row->id_gudang)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->gudang;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_gudang = $this->m_admin->kondisi("ms_gudang","id_gudang != '$row->id_gudang'");                                                
                      foreach($dt_gudang->result() as $val) {
                        echo "
                        <option value='$val->id_gudang'>$val->gudang</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div> 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kolom</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?php echo $row->kolom ?>" required id="inputEmail3" placeholder="Kolom" name="kolom">
                  </div>
                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Qty</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->qty ?>" required id="inputEmail3" placeholder="Qty" name="qty">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Baris</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?php echo $row->baris ?>" required id="inputEmail3" placeholder="Baris" name="baris">
                  </div>
                                   
                
                 <label for="inputEmail3" class="col-sm-2 control-label">Tipe Dedicated</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="tipe_dedicated">
                      <option value="<?php echo $row->tipe_dedicated ?>"><?php echo $row->tipe_dedicated ?></option>
                      <option value="">- choose -</option>
                      <?php                       
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan - $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Unit</label>
                  <div class="col-sm-4">
                    <?php 
                    $cek_slot = $this->db->query("SELECT lokasi,slot FROM tr_scan_barcode WHERE lokasi = '$row->id_lokasi_unit' AND status = 1 ORDER BY slot DESC LIMIT 0,1");
                    if($cek_slot->num_rows() > 0){
                      $is = "disabled";
                    }else{
                      $is = "";
                    }
                    ?>
                    <select class="form-control" name="status_unit" <?php echo $is ?>>
                      <option value="<?php echo $row->status_unit ?>"><?php echo $row->status_unit ?></option>
                      <?php 
                      if($row->status_unit == 'RFS'){
                      ?>                      
                        <option>NRFS</option>
                        <option>Pinjaman</option>
                      <?php 
                      }elseif($row->status_unit == 'NRFS'){
                      ?>
                        <option>RFS</option>
                        <option>Pinjaman</option>
                      <?php 
                      }elseif($row->status_unit == 'Pinjaman'){
                      ?>
                        <option>RFS</option>
                        <option>NRFS</option>
                      <?php } ?>
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
          <a href="master/lokasi_unit/add">
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
              <th>ID Lokasi Unit</th>              
              <th>Lantai</th>              
              <th>Kolom</th>
              <th>Baris</th>
              <th>Qty</th>
              <th>Gudang</th>
              <th>Dedicated</th>              
              <th>Status Unit</th>
              <th width="5%">Active</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_lokasi_unit->result() as $row) { 
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";                  

            $isi = $this->db->query("SELECT COUNT(id_scan_barcode) AS jum FROM tr_scan_barcode WHERE lokasi = '$row->id_lokasi_unit' AND (status = 1 OR status = 2)")->row();                     
          echo "          
            <tr>
              <td>$no</td>
              <td>
                <a href='master/lokasi_unit/detail?id=$row->id_lokasi_unit'>
                  $row->id_lokasi_unit ($isi->jum)
                </a>
              </td>                            
              <td>$row->lantai</td>                            
              <td>$row->kolom</td>
              <td>$row->baris</td>                            
              <td>$row->qty</td>                            
              <td>$row->gudang</td>                            
              <td>$row->tipe_dedicated</td>                            
              <td>$row->status_unit</td>                            
              <td>$active</td>              
              <td>";
              if($isi->jum == 0){
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/lokasi_unit/delete?id=<?php echo $row->id_lokasi_unit ?>"><i class="fa fa-trash-o"></i></a>              
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/lokasi_unit/edit?id=<?php echo $row->id_lokasi_unit ?>'><i class='fa fa-edit'></i></a>
              <?php 
              }elseif($isi->jum <= $row->qty){ 
              ?> 
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/lokasi_unit/edit?id=<?php echo $row->id_lokasi_unit ?>'><i class='fa fa-edit'></i></a>
              <?php }else{ ?>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/lokasi_unit/edit?id=<?php echo $row->id_lokasi_unit ?>'><i class='fa fa-edit'></i></a>
              <?php } ?>
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
    }elseif($set=="detail"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/lokasi_unit">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>          
          
          <!--a href="h1/invoice/upload">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
          </a-->          
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Lokasi</th>              
              <th>Slot</th>              
              <th>No Mesin</th>              
              <th>Kode Item</th>
              <th>Status</th>                            
              <th>FIFO</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_lokasi->result() as $row) {  
            if($row->status=='1'){
              $status = "<span class='label label-success'>ready</span>";
            }elseif($row->status=='2'){
              $status = "<span class='label label-warning'>booking</span>";
            }elseif($row->status=='3'){
              $status = "<span class='label label-primary'>process</span>";
            }elseif($row->status=='4'){
              $status = "<span class='label label-success'>sold</span>";
            }                  
          echo "          
            <tr>
              <td>$row->lokasi</td>
              <td>$row->slot</td>
              <td>$row->no_mesin</td>              
              <td>$row->id_item</td>              
              <td>$status</td>              
              <td>$row->fifo</td>              
            </tr>";                        
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
          url: "<?php echo site_url('master/lokasi_unit/ajax_bulk_delete')?>",
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