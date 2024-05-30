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
    <li class="">Demography</li>
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
          <a href="master/provinsi">
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
            <form class="form-horizontal" action="master/provinsi/save" method="post" id="masuk_form" enctype="multipart/form-data">
              <div class="box-body">   
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Provinsi</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="id_provinsi" placeholder="ID Provinsi" name="id_provinsi">
                  </div>
                </div>                                                                                           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" required id="provinsi" placeholder="Provinsi" name="provinsi">
                  </div>
                </div>                                                            
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                    
                    
                    
                    <!-- Aku titip disini pak -->
                        <button type='submit' name='save' value='save' class='btn btn-info btn-flat'><i class='fa fa-edit'></i> Save</button>                
                  		<button type='reset' class='btn btn-default btn-flat'><i class='fa fa-refresh'></i> Cancel</button>
                  	<!-- /.Aku titip disini pak -->
                  	
                  	
                  	
                  	
                  <?php $akses ?>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="rawbt"){
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>

function ajax_print(url, btn) {
	console.log(url);
    $.get(url, function (data) {
      console.log(data);
		var ua = navigator.userAgent.toLowerCase();
		var isAndroid = ua.indexOf("android") > -1;
		if(isAndroid) {
		    android_print(data);
		}else{
		    pc_print(data);
		}
    });
}

function android_print(data){
    window.location.href = data;
}

function pc_print(data){
    var socket = new WebSocket("ws://127.0.0.1:40213/");
    socket.bufferType = "arraybuffer";
    socket.onerror = function(error) {
	  alert("Error");
    };
	socket.onopen = function() {
		socket.send(data);
		socket.close(1000, "Work complete");
	};
}

</script>

<?php 
// Check if the "mobile" word exists in User-Agent 
$isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile")); 
 
if($isMob){ 
    echo 'Using Mobile Device...'; 
}else{ 
    echo 'Using Desktop...'; 
}

// include('rawbt.php');

?>

<button onclick="ajax_print('sample_receipt.php',this)">RECEIPT</button>




<?php
    }elseif($set=="edit"){
      $row = $dt_provinsi->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="master/provinsi">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="master/provinsi/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_provinsi ?>" />
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
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Provinsi</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="inputEmail3" value="<?php echo $row->id_provinsi; ?>" placeholder="ID Provinsi" name="id_provinsi">
                  </div>
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Provinsi</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" id="inputEmail3" value="<?php echo $row->provinsi; ?>" placeholder="Provinsi" name="provinsi">
                  </div>
                </div>                                                            
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <?php echo $akses ?>
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
          <a href="master/provinsi/add">
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
              <th width="10%">ID Provinsi</th>
              <th>Provinsi</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_provinsi->result() as $row) {       
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->id_provinsi</td>
              <td>$row->provinsi</td>              
              <td>";
              ?>
                <a data-toggle='tooltip' title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="master/provinsi/delete?id=<?php echo $row->id_provinsi ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='master/provinsi/edit?id=<?php echo $row->id_provinsi ?>'><i class='fa fa-edit'></i></a>
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
          url: "<?php echo site_url('master/provinsi/ajax_bulk_delete')?>",
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/validation/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/validation/jquery-1.7.1.min.js"></script>

<script type="text/javascript">   
$(document).ready(function(){
    $('#masuk_form').validate({
      rules: {
        id_provinsi: {
          required: true,
          maxlength: 5,
          number: true
          },      
        provinsi: {          
          required: true
        }      
      
      },
      highlight: function(element) {
        $(element).closest('.control-group').removeClass('success').addClass('error');
      },
      success: function(element) {
        element
        .text('OK!').addClass('valid')
        .closest('.control-group').removeClass('error').addClass('success');
      }
    });
}); // end document.ready
</script>