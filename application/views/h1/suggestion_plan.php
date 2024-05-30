<?php 
function bln($bulan){
  //$bulan=$bl=$month=date("m");
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
    <li class="">Distribusi Unit</li>
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
          <a href="h1/suggestion_plan">
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
            <form class="form-horizontal" action="h1/suggestion_plan/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <input type="hidden" name="id_suggestion_displan" id="id_suggestion_displan">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="bulan" id="bulan">
                      <option value="">- choose -</option>
                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mei</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="tahun" id="tahun">
                      <option value="">- choose -</option>
                      <?php 
                      $ty = date("Y");
                      for ($i=$ty - 10; $i <= $ty + 10; $i++) { 
                        echo "<option value='$i'>$i</option>";
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Persen AHM</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Persen AHM" name="ahm" id="ahm">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Persen MD</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" placeholder="Persen MD" name="md" id="md">                    
                  </div>
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-4">
                    <button type="button" onclick="generate()" class="btn btn-flat btn-primary" type="button"><i class="fa fa-refresh"></i> Generate</button>
                  </div>                  
                </div>                                  
                <hr>                
                <div class="form-group">                
                  <span id="tampil_sd"></span>                                                                                  
                </div>                      
                
                                                      
                                    
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
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
          <a href="h1/suggestion_plan/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Generate New</button>
          </a>          
          <!-- <a href="h1/suggestion_plan/dealer_tipe">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-list"></i> Per Dealer Per Tipe</button>
          </a>                     -->
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
              <th>Kode Tipe Kendaraan</th>              
              <th>Tipe Kendaraan</th>              
              <th>Bulan</th>
              <th>Tahun</th>
              <th>Status</th>              
              <!-- <th>Action</th>               -->
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;
          foreach ($dt_sp->result() as $row) {
            $tom = "<a href='h1/suggestion_plan/approval?id=$row->id_suggestion_plan&i=$row->id_tipe_kendaraan' type='button' class='btn btn-flat btn-success btn-xs'><i class='fa fa-check'></i> Approval</a>
            <a type='button' class='btn btn-flat btn-primary btn-xs'><i class='fa fa-edit'></i> Edit</a>";
            echo "
            <tr>
              <td>$no</td>
              <td>$row->id_tipe_kendaraan</td>
              <td>$row->tipe_ahm</td>
              <td>$row->bulan</td>
              <td>$row->tahun</td>
              <td>$row->status_sp</td>              
            </tr>
            ";
            $no++;
          }
          ?>
<!-- 
          <td>";
              echo $tom;
              echo "</td> -->
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
  $("#tampil_sd").show();
  var bulan = $("#bulan").val();
  var tahun = $("#tahun").val();
  var ahm = $("#ahm").val();
  var md = $("#md").val();  
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "bulan="+bulan+"&tahun="+tahun+"&ahm="+ahm+"&md="+md;                           
     xhr.open("POST", "h1/suggestion_plan/t_sd", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_sd").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }   
}
</script>

