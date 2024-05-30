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
    <li class="">BBN</li>
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
          <a href="master/bbn_dealer">
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
            <form class="form-horizontal" action="master/bbn_dealer/save" method="post" enctype="multipart/form-data">
              <div class="box-body">    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID BBN MD</label>
                  <div class="col-sm-4">
                    <input type="hidden" id="tgl" value="<?php date('Y-m-d') ?>">
                    <input type="text" class="form-control" required readonly id="id_bbn_dealer" placeholder="ID BBN MD" name="id_bbn_dealer">
                  </div>
                </div>                    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_tipe_kendaraan" required>
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_tipe_kendaraan->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan | $val->deskripsi_ahm |$val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control tanpa_rupiah" required placeholder="Biaya BBN" name="biaya_bbn">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Instansi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control tanpa_rupiah" required placeholder="Biaya Instansi" name="biaya_instansi">
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
      $row = $dt_bbn_dealer->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/bbn_dealer">
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
            <form class="form-horizontal" action="master/bbn_dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_bbn_dealer ?>" />
              <div class="box-body">    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID BBN MD</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" readonly value="<?php echo $row->id_bbn_dealer ?>" required id="inputEmail3" placeholder="ID BBN MD" name="id_bbn_dealer">
                  </div>
                </div>                                                                      
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_tipe_kendaraan" required>
                      <option value="<?php echo $row->id_tipe_kendaraan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan)->row();                                 
                        if(isset($dt_cust)){
                         echo "$dt_cust->id_tipe_kendaraan | $dt_cust->deskripsi_ahm |$dt_cust->tipe_ahm";
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_tipe_kendaraan = $this->m_admin->kondisi("ms_tipe_kendaraan","id_tipe_kendaraan != '$row->id_tipe_kendaraan'");                                                
                      foreach($dt_tipe_kendaraan->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan | $val->deskripsi_ahm |$val->tipe_ahm</option>;
                        ";

                      }
                      ?>
                    </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control tanpa_rupiah" value="<?php echo $row->biaya_bbn ?>" required  placeholder="Biaya BBN" name="biaya_bbn">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Biaya Instansi</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control tanpa_rupiah" value="<?php echo $row->biaya_instansi ?>" required placeholder="Biaya Instansi" name="biaya_instansi">
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
          <a href="master/bbn_dealer/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          

          <button class="btn bg-green btn-flat margin" data-toggle="modal" data-target="#modalImport"><i class="fa fa-upload"></i> Import Data</button>

          <!-- Modal -->
          <div class="modal fade" id="modalImport" role="dialog">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Import Data BBN Dealer</h4>
                </div>
                <div class="modal-body">
                  <form action="<?php echo base_url() ?>master/bbn_dealer/proses_import" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                      <a href="<?php echo base_url() ?>uploads/template_import_bbn_dealer.xlsx" target="_blank" class="label label-success">Download Template</a>
                    </div>
                    <div class="form-group">
                      <label>File Import</label>
                      <input type="file" name="import_file" class="form-control">
                    </div>
                    <div class="form-group">
                      
                    </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Import Sekarang</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </form>
                </div>
              </div>
              
            </div>
          </div>

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
              <th>ID BBN MD</th>                            
              <th>Tipe</th>
              <th>Biaya BBN</th>
              <th>Biaya Instansi</th>
              <th>Tgl Efektif Harga</th>
              <th width="5%">Active</th>                            
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_bbn_dealer->result() as $row) {          
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
            $rp = mata_uang($row->biaya_bbn);
            $rp2 = mata_uang($row->biaya_instansi);
          echo "          
            <tr>
              <td>$no</td>              
              <td>$row->id_bbn_dealer</td>                            
              <td>$row->id_tipe_kendaraan | $row->deskripsi_ahm |$row->tipe_ahm</td>                            
              <td>$rp</td>                            
              <td>$rp2</td>                           
              <td>$row->tgl_efektif</td>                            
              <td>$active</td>                            
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/bbn_dealer/delete?id=<?php echo $row->id_bbn_dealer ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/bbn_dealer/edit?id=<?php echo $row->id_bbn_dealer ?>'><i class='fa fa-edit'></i></a>
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
      url : "<?php echo site_url('master/bbn_dealer/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_bbn_dealer").val(data[0]);                               
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
          url: "<?php echo site_url('master/bbn_dealer/ajax_bulk_delete')?>",
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