<?php 
function bln(){
  $bulan=$bl=$month=date("m");
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>
<base href="<?php echo base_url(); ?>" />
<?php 
if(isset($_GET['id'])){
?>
<body onload="cek_jenis()">
<?php }else{ ?>
<body onload="auto()">
<?php } ?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Generate File</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="generate"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/cdb">
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
            <form class="form-horizontal" action="h1/cdb/unduh"  method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date *</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" autocomplete="off" name="start_date" id="tanggal1" placeholder="Start Date">                    
                  </div>                  
                </div>
		                                                                                                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">End Date *</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" autocomplete="off" name="end_date" id="tanggal2" placeholder="End Date">                    
                  </div>                 
                </div>        
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" autocomplete="off" name="no_mesin" id="no_mesin" placeholder="No Mesin">                    
                  </div>                  
                  <div class="col-sm-4">
                    <button type="button" onclick="generate()" class="btn btn-primary btn-flat"><i class="fa fa-refresh"></i> Generate Data</button>
                  </div>                  
                </div>   
                                                               
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Extension File </label>
                  <div class="col-sm-4">
                    <input type="checkbox" id="cdb" name="cdb" value="1" checked>
                    <label for="vehicle1"> .CDB</label> &emsp;
                    <input type="checkbox" id="ustk" name="ustk" value="2" checked>
                    <label for="vehicle2"> .USTK </label> &emsp;
                    <input type="checkbox" id="kk" name="kk" value="3" checked>
                    <label for="vehicle3"> .KK </label>
                  </div>    
                </div>    
                                                    
                <div class="form-group">
                  <div id="tampil_cdb"></div>
                </div>                                                                 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to create this data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Create File </button>                                  
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
          <!--a href="h1/po/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
          
          <a href="h1/cdb/generate">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-refresh"></i> Generate</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>            
              <th width="5%">No</th>
              <th>Start Date</th>              
              <th>End Date</th>              
              <th>Nama File</th>                            
              <th width="5%">Aksi</th>                            
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_cdb->result() as $row) {          
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->start_date</td>              
              <td>$row->end_date</td>
              <td>$row->nama_file</td>              
              <td>
                <a href='h1/cdb/download?id=$row->id_cdb_generate' class='btn btn-primary btn-flat btn-sm'><i class='fa fa-download'></i> Download</button>
              </td>              
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
function generate(){    
  $("#tampil_cdb").show();
  var start_date = document.getElementById("tanggal1").value;   
  var end_date = document.getElementById("tanggal2").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "start_date="+start_date+"&end_date="+end_date;                           
     xhr.open("POST", "h1/cdb/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {      
              console.log(xhr); 
                document.getElementById("tampil_cdb").innerHTML = xhr.responseText;       
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>