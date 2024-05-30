<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;  
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<base href="<?php echo base_url(); ?>" />
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Faktur STNK</li>
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
          <a href="h1/report_promosi">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="h1/report_promosi/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <input type="hidden" name="id_report_promosi" id="id_report_promosi">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Reg</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="no_reg" name="no_reg" onchange="ambil_noreg()">
                      <option value="">- choose -</option>
                      <?php 
                      $dt_promosi = $this->m_admin->getAll("tr_promosi");
                      foreach ($dt_promosi->result() as $isi) {
                        echo "<option value='$isi->no_reg'>$isi->no_reg</option>";
                      }
                      ?>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Lokasi</label>
                  <div class="col-sm-4">
                    <input type="text" name="lokasi" id="lokasi" placeholder="Lokasi" readonly class="form-control">
                  </div>                                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Mulai</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_mulai" id="tgl_mulai" placeholder="Tgl Mulai" readonly class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Selesai</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_selesai" id="tgl_selesai" placeholder="Tgl Selesai" readonly class="form-control">
                  </div>                                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Cuaca</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_cuaca">
                      <option value="">- choose -</option>
                      <?php 
                      $cuaca = $this->m_admin->getAll("ms_cuaca");
                      foreach ($cuaca->result() as $isi) {
                        echo "<option value='$isi->id_cuaca'>$isi->cuaca</option>";
                      }
                      ?>
                    </select>
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jumlah Orang</label>
                  <div class="col-sm-4">
                    <input type="text" name="jum_orang" placeholder="Jumlah Orang" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                  <div class="col-sm-4">
                    <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Biaya yg Dikeluarkan</label>
                  <div class="col-sm-4">
                    <input type="text" name="total" placeholder="Total Biaya" class="form-control">
                  </div>                                
                </div>


                <div class="form-group">
                  <table class="table" >
                    <tr>
                      <th width="70%">Foto</th>
                      <th width="20%">Foto</th>
                      <th width="10%">Action</th>                    
                    </tr>
                    <tbody>
                      <tr>
                        <td><input type="file" name="nama_file[]" class="form-control"></td>                                          
                        <td><input type="text" name="ket[]" placeholder="Ket" class="form-control"></td>                                          
                        <td><button type="button" class="btn btn-danger remove"> -</button type="button"></td>
                      </tr>
                      <tr>
                        <td><input type="file" name="nama_file[]" class="form-control"></td>                                          
                        <td><input type="text" name="ket[]" placeholder="Ket" class="form-control"></td>                                          
                        <td><button type="button" class="btn btn-danger remove"> -</button type="button"></td>
                      </tr>
                    </tbody>
                    <tbody id="append"></tbody>
                  </table>                                                                   
                  <input type="hidden" value="1" id="hide">
                  <div class="pull-right">
                  <button type="button" class="btn btn-primary add" style="margin-right: 5px;margin-top: -15px;">+</button type="button">   
                </div><!-- /.box-body -->                

              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
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
          <a href="h1/report_promosi/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>ID Program</th>
              <th>No reg</th>
              <th>Tgl Reg</th>              
              <th>Tema</th>            
              <th>Jenis</th>
              <th>Tgl Mulai</th>              
              <th>Tgl Selesai</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_report->result() as $row) {                                         
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->id_program_promosi</td>                           
              <td>$row->no_reg</td>                           
              <td>$row->tgl_reg</td>                           
              <td>$row->tema</td>                            
              <td>$row->jenis_promosi</td>                            
              <td>$row->tgl_mulai</td>
              <td>$row->tgl_selesai</td>                                         
              ";                                      
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
  var id = "1";
  $.ajax({
      url : "<?php echo site_url('h1/report_promosi/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_report_promosi").val(data[0]);
        //kirim_data();             
      }        
  })
}
function ambil_noreg(){
  var no_reg = document.getElementById("no_reg").value; 
  $.ajax({
      url : "<?php echo site_url('h1/report_promosi/ambil_noreg')?>",
      type:"POST",
      data:"no_reg="+no_reg,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#lokasi").val(data[0]);
        $("#tgl_mulai").val(data[1]);
        $("#tgl_selesai").val(data[2]);
        //kirim_data();             
      }        
  })
}
</script>

<script type="text/javascript" id="lancar">
$(document).ready(function(){
  $('.add').click(function(){
    var start=$('#hide').val();
    var sumall=Number(start)+1;
    $('#hide').val(sumall);
    var tbody=$('#append');
    $('<tr><td><input type="file" name="nama_file[]" class="form-control"></td><td><input type="text" name="ket[]" placeholder="Ket" class="form-control"></td><td><button type="button" class="btn btn-danger remove">-</button type="button"></td></tr>').appendTo(tbody);
    $('.remove').click(function(){     
      $(this).parents('tr').remove();      
    });
  });  
});
</script>