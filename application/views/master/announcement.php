<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li class=""><i class="fa fa-database"></i> Master Data</li>
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
          <a href="master/announcement">
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
            <form class="form-horizontal" action="master/announcement/save" method="post" enctype="multipart/form-data">
              <div class="box-body">           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Perihal *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="perihal" placeholder="Perihal" maxlength="255" name="perihal">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Aktif *</label>
		  <div class="col-sm-4">
                    <input type="text" id="tanggal" required class="form-control" name="start_date" placeholder="Tgl Aktif" autocomplete="off">
                  </div>

                </div>  
                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tujuan *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="untuk" required>
                      <option value="">- choose -</option>
                      <option value="1">MD & Dealer</option>
                      <option value="2">Main Dealer</option>
                      <option value="3">Dealer</option>
                    </select>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Expired *</label>
		  <div class="col-sm-4">
                    <input type="text" id="tanggal2" required class="form-control" name="end_date" placeholder="Tgl Expired" autocomplete="off">
                  </div>
                
                </div>

		<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Isi *</label>
                  <div class="col-sm-4">
		    <textarea id="isi" required name="isi" rows="4" cols="50" style="width:100%" placeholder="   Isi"></textarea>
                  </div>
                </div>  

		<div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="1" checked>
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
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="edit"){
      $row = $get_data; 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/announcement">
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
            <form class="form-horizontal" action="master/announcement/update" method="post" enctype="multipart/form-data">
              <div class="box-body">    
		<input type="hidden" name="id" value="<?php echo $row->id ?>" />       
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Perihal *</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required id="perihal" value="<?php echo $row->perihal ?>"  placeholder="Perihal" maxlength="255" name="perihal">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Aktif *</label>
		  <div class="col-sm-4">
                    <input type="text" id="tanggal" required class="form-control" value="<?php echo $row->tgl_aktif ?>" name="start_date" placeholder="Tgl Aktif" autocomplete="off">
                  </div>

                </div>  
                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tujuan *</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="untuk" required>
                      <option value="">- choose -</option>
                      <option value="1" <?php if($row->untuk == '1'){echo 'selected';} ?> >MD & Dealer</option>
                      <option value="2" <?php if($row->untuk == '2'){echo 'selected';} ?>>Main Dealer</option>
                      <option value="3" <?php if($row->untuk == '3'){echo 'selected';} ?>>Dealer</option>
                    </select>
                  </div>  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Expired *</label>
		  <div class="col-sm-4">
                    <input type="text" id="tanggal2" required class="form-control" value="<?php echo $row->tgl_expired ?>" name="end_date" placeholder="Tgl Expired" autocomplete="off">
                  </div>
                
                </div>

		<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Isi *</label>
                  <div class="col-sm-4">
		    <textarea id="isi" required name="isi" rows="4" cols="50" style="width:100%" placeholder="   Isi"><?php echo $row->isi ?></textarea>
                  </div>
                </div>  

		<div class="form-group">                  
                  <label for="field-1" class="col-sm-2 control-label"></label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="<?php echo $row->active ?>" <?php if($row->active == '1'){echo 'checked';} ?>>
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
          <a href="master/announcement/add">
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
        <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Perihal</th>          
              <th>Untuk</th>           
              <th>Tgl Aktif</th>
              <th>Tgl Expired</th>                            
              <th>Active</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>                      
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

var table;

$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master/announcement/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0,5 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });
});

</script>
<script type="text/javascript">
function cek_kode(){
  var kodepos = $("#kodepos").val();  
  if(kodepos.length > 5){
    alert("Max 5 character");
  }else{
    document.getElementById("kodepos").readOnly = false;    
  }
}
</script>