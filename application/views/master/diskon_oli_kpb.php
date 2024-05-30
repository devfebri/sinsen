<base href="<?php echo base_url(); ?>" />
<body onload="kirim_data()">
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
          <a href="master/diskon_oli_kpb">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/diskon_oli_kpb/save" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Part *</label>            
                  <div class="col-sm-4">
                    <input type="text" readonly name="id_part" data-toggle="modal" placeholder="Kode Part" data-target="#Partmodal" class="form-control" id="id_part" onchange="take_part()">                                    
                  </div>                
                  <label for="field-1" class="col-sm-2 control-label">Nama Part</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Part" name="nama_part" id="nama_part" readonly>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Tipe Kendaraan</label>           
                  <div class="col-sm-4">
                    <select name="id_tipe_kendaraan" class="form-control select2">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_tipe->result() as $isi) {
                        echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tipe Diskon</label>           
                  <div class="col-sm-4">
                    <select name="tipe_diskon" class="form-control">
                      <option value="">- choose -</option>
                      <option>Rupiah</option>
                      <option>Persen</option>
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Diskon Oli</label>           
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Diskon Oli" name="diskon_oli">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="status" value="1" checked>
                      Active
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
      $row = $dt_diskon_oli_kpb->row();
    ?>    
    <body onload="take_gudang()">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/diskon_oli_kpb">
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
            <form method="POST" role="form" enctype="multipart/form-data" action="master/diskon_oli_kpb/update" class="form-horizontal form-groups-bordered">
              <div class="box-body">                                                                                                                                                    
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Part *</label>            
                  <div class="col-sm-4">
                    <input type="text" readonly value="<?php echo $row->id_part ?>" readonly name="id_part" placeholder="Kode Part" class="form-control" id="id_part" onchange="take_part()">                                    
                  </div>                
                  <label for="field-1" class="col-sm-2 control-label">Nama Part</label>            
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nama Part" name="nama_part" id="nama_part" value="<?php echo $row->nama_part ?>" readonly>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Kode Tipe Kendaraan</label>           
                  <div class="col-sm-4">
                    <select name="id_tipe_kendaraan" class="form-control select2">
                      <option value="">- choose -</option>
                      <?php 
                      foreach ($dt_tipe->result() as $isi) {
                        $hasil = ($isi->id_tipe_kendaraan == $row->id_tipe_kendaraan) ? "selected" : "" ;
                        echo "<option value='$isi->id_tipe_kendaraan' $hasil>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-2 control-label">Tipe Diskon</label>           
                  <div class="col-sm-4">
                    <select name="tipe_diskon" class="form-control">
                      <option <?php if($row->tipe_diskon == "") echo "selected" ?> value="">- choose -</option>
                      <option <?php if($row->tipe_diskon == "Rupiah") echo "selected" ?>>Rupiah</option>
                      <option <?php if($row->tipe_diskon == "Persen") echo "selected" ?>>Persen</option>
                    </select>
                  </div>
                  <label for="field-1" class="col-sm-2 control-label">Diskon Oli</label>           
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->diskon_oli ?>" placeholder="Diskon Oli" name="diskon_oli">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->status2=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="status" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="status" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>  
                </div>
                <div class="box-footer">
                  <div class="col-sm-2">
                  </div>
                  <div class="col-sm-10">
                    <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update</button>
                    <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                  </div>
                </div><!-- /.box-footer -->                
              </div>
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
          <a href="master/diskon_oli_kpb/add">
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
              <th>Kode Part</th>
              <th>Nama Part</th>             
              <th>Tipe Kendaraan</th>              
              <th>Tipe Diskon</th>              
              <th>Diskon Oli</th>              
              <th>Aksi</th>                            
            </tr>
          </thead>
          <tbody>
            <?php 
            $no=1;
            foreach ($dt_diskon_oli_kpb->result() as $val) {                
              echo"
              <tr>
                <td>$no</td>
                <td>$val->id_part</td>
                <td>$val->nama_part</td>
                <td>$val->tipe_ahm</td>                
                <td>$val->tipe_diskon</td>                
                <td>$val->diskon_oli</td>                
                <td>"; ?>
                  <a href="master/diskon_oli_kpb/delete?id=<?php echo $val->id_diskon_kpb ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                  <a href="master/diskon_oli_kpb/edit?id=<?php echo $val->id_diskon_kpb ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="Edit"><i class="fa fa-edit"></i></button></a>                  
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
<div class="modal fade" id="Partmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Part
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
       <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>              
              <th>Kode Part</th>
              <th>Nama Part</th>
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>                     
          </tbody>
        </table>        
      </div>      
    </div>
  </div>
</div>


<script type="text/javascript">
function take_part(){
  var id_part = $("#id_part").val();                       
  $.ajax({
      url: "<?php echo site_url('master/diskon_oli_kpb/take_part')?>",
      type:"POST",
      data:"id_part="+id_part,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");                    
          $("#id_part").val(data[0]);                                                    
          $("#nama_part").val(data[1]);                                                              
      } 
  })
}
function chooseitem(id_part){
  document.getElementById("id_part").value = id_part; 
  take_part();
  $("#Partmodal").modal("hide");
}
</script>
<script type="text/javascript">
var table;
$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master/diskon_oli_kpb/ajax_list')?>",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });
});
</script>