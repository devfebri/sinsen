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
            <form class="form-horizontal" action=""  method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Transaksi</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" autocomplete="off" name="start_date" id="tanggal1" placeholder="Tgl Transaksi">                    
                  </div>                  
                </div>
		                 
                <?php /*
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" autocomplete="off" name="end_date" id="tanggal2" placeholder="End Date">                    
                  </div>               
                </div>  
                */?>      
                                                               
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Extension File </label>
                  <div class="col-sm-4">
                    <input type="radio" id="REC" name="generate_file" value="REC" checked> <label for="REC"> .REC</label><br>
                    <input type="radio" id="STO" name="generate_file" value="STO"> <label for="STO"> .STO</label><br>
                    <input type="radio" id="POD" name="generate_file" value="POD"> <label for="POD"> .POD</label><br>
                    <input type="radio" id="SAL" name="generate_file" value="SAL"> <label for="SAL"> .SAL</label>
                  </div>  
                </div>    
                                                          
                <div class="form-group">
                  <div id="tampil_data"></div>
                </div>                         
                <div class="form-group">  
                  <div class="col-sm-2">
                  </div>   
                  <div class="col-sm-4">
                    <button type="button" onclick="generate()" id='submit_btn' class="btn btn-primary btn-flat"><i class="fa fa-download"></i> Download File</button>
                  </div> 
                </div>                                                                 
              </div><!-- /.box-body -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>

<script type="text/javascript">
  function generate(){    
    var start_date = document.getElementById("tanggal1").value;   
    var file = $("input[name='generate_file']:checked").val();

    var xhr;
    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
      xhr = new XMLHttpRequest();
    }else if (window.ActiveXObject) { // IE 8 and older
      xhr = new ActiveXObject("Microsoft.XMLHTTP");
    } 

    $("#submit_btn").attr("disabled", true);     

    if(start_date ==''){
      alert('Silahkan isi dahulu periode waktunya');
    }else{
      var data = "start_date="+start_date+"&ext_file="+file;                      
      xhr.open("POST", "h3/H3_md_file_ahm/download", true); 
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
      xhr.send(data);
      xhr.onreadystatechange = display_data;

      function display_data() {        
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {      
              document.getElementById("tampil_data").innerHTML = xhr.responseText;    
            }else{
              alert('There was a problem with the request.');
            }
        }
        
        $("#submit_btn").attr("disabled", false);   
      }  
    }
  }
</script>