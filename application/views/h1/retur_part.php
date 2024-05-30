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
<body onload="getDetail()">
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
          <a href="h1/retur_part">
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
            <form class="form-horizontal" action="h1/retur_part/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                
                <!--<div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">
                    <select class="form-control select2">
                      <option>- choose -</option>
                      <?php $no_po = $this->db->query("SELECT * from tr_po_aksesoris where status_po='terpenuhi'");
                      	foreach($no_po->result() as $no_po) { ?>
                      	<option value="<?php echo $no_po->no_po_aksesoris ?>"><?php echo $no_po->no_po_aksesoris ?></option>
                      <?php } ?>
                    </select>
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No WO</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="No WO" readonly class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="No Surat Jalan" readonly class="form-control">
                  </div>                                
                </div>-->
                <!--<div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">
                    <select class="form-control select2">
                      <option>- choose -</option>
                      <?php $no_po = $this->db->query("SELECT * from tr_po_aksesoris where status_po='terpenuhi'");
                      	foreach($no_po->result() as $no_po) { ?>
                      	<option value="<?php echo $no_po->no_po_aksesoris ?>"><?php echo $no_po->no_po_aksesoris ?></option>
                      <?php } ?>
                    </select>
                  </div>                  
                </div> -->

                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Retur</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="jenis_retur" name="jenis_retur" onchange="jenis_Retur()">
                      <option value="">- choose -</option>
                      <option value="po_checker">PO Checker</option>
                      <option value="po_aksesoris">PO Aksesoris</option>
                    </select>
                  </div>       
                  <label for="inputEmail3" class="col-sm-2 control-label">No. Surat Jalan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" id="no_sj" name="no_sj" onchange="jenis_ReturNoSJ()">
                      <option value="">- choose -</option>
                    </select>
                  </div>                  
                </div>
                <!--<div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No WO</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="No WO" readonly class="form-control">
                  </div>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Surat Jalan</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="No Surat Jalan" readonly class="form-control">
                  </div>                                
                </div>-->
<hr>
                <div id="tampil_detail"></div>

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
    }
    elseif($set=="detail"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/retur_part">
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
            <form class="form-horizontal" action="h1/retur_part/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <table>
                  <tr>
                    <td>No. Retur Part</td><td>:</td><td><?php echo $retur->no_retur_part ?></td>
                  </tr>
                   <tr>
                    <td>Jenis Retur</td><td>:</td><td><?php echo $retur->jenis_retur ?></td>
                  </tr>
                </table>
<hr>
                <table id="myTable" class="table myTable1 order-list" border="0">
                 <thead>
                  <tr>
                    <th width="15%">Kode Part</th>
                    <th width="10%">Nama Part</th>      
                    <th width="10%">Qty Retur</th>                      
                    <th width="10%">Alasan Retur</th>                       
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($detail_retur_part)) { ?>  
                  <?php foreach ($detail_retur_part->result() as $retur): ?>
                    <tr>
                      <td><?php echo $retur->id_part ?></td>
                      <td><?php echo $retur->nama_part ?></td>
                      <td><?php echo $retur->qty_retur ?></td>
                      <td><?php echo $retur->alasan_retur ?></td>
                    </tr>
                  <?php endforeach ?>
                <?php } ?>
              </tbody>
            </table>

              </div><!-- /.box-body -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php 
    }
    elseif($set=="approve"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/retur_part">
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
            <form class="form-horizontal" action="h1/retur_part/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <table>
                  <tr>
                    <td>No. Retur Part</td><td>:</td><td><?php echo $retur->no_retur_part ?></td>
                  </tr>
                   <tr>
                    <td>Jenis Retur</td><td>:</td><td><?php echo $retur->jenis_retur ?></td>
                  </tr>
                </table>
<hr>
                <table id="myTable" class="table myTable1 order-list" border="0">
                 <thead>
                  <tr>
                    <th width="15%">Kode Part</th>
                    <th width="10%">Nama Part</th>      
                    <th width="10%">Qty Retur</th>                      
                    <th width="10%">Alasan Retur</th>                       
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($detail_retur_part)) { ?>  
                  <?php foreach ($detail_retur_part->result() as $retur): ?>
                    <tr>
                      <td><?php echo $retur->id_part ?></td>
                      <td><?php echo $retur->nama_part ?></td>
                      <td><?php echo $retur->qty_retur ?></td>
                      <td><?php echo $retur->alasan_retur ?></td>
                    </tr>
                  <?php endforeach ?>
                <?php } ?>
              </tbody>
            </table>

              </div><!-- /.box-body -->
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
          <a href="h1/retur_part/add">            
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>No Retur Part</th>
              <th>Jenis Retur</th>
              <th>No PO</th>              
              <th>No Surat Jalan</th>            
              <th>Qty Retur</th>
              <th>Status</th>              
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
           $no=1;
           $retur_part = $this->db->query("SELECT *,tr_retur_part.no_retur_part as no, (select sum(qty_retur) as jum from tr_retur_part_detail where id_retur_part=no) as jum from tr_retur_part where status<>'new' "); 
          foreach($retur_part->result() as $row) {    
            if ($row->jenis_retur=='po_checker') {
               $jenis_retur = 'PO Checker';
             }elseif($row->jenis_retur=='PO Aksesoris')
             {
              $jenis_retur = 'PO Aksesoris';
             }else{
              $jenis_retur = "";
             }           
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');

          echo "         
            <tr>
              <td>$no</td>
              <td>$row->no_retur_part</td>                           
              <td>$jenis_retur</td>                           
              <td>$row->no_po</td>                           
              <td>$row->no_sj</td>                                                        
              <td>$row->jum</td>                                                        
              <td>$row->status</td>                             
              <td>
                <a class='btn btn-flat btn-warning btn-xs' href='h1/retur_part/detail/$row->no_retur_part'>view</a> ";?>
                  <?php if ($row->status=='input'): ?>
                   <?php echo " <a class='btn btn-flat btn-success btn-xs' $approval href='h1/retur_part/approve/$row->no_retur_part'>approve</a> " ?>
                  <?php endif ?>
                <?php echo "
                
              </td>
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
  function getDetail() {
       $.ajax({
               url:"<?php echo site_url('h1/retur_part/getDetail');?>",
               type:"POST",
               cache:false,
               success:function(html){
                  $("#tampil_detail").html(html);
               }
          });
  }

  function jenis_Retur()
  {
    var jenis_retur = $('#jenis_retur').val();
    $.ajax({
               url:"<?php echo site_url('h1/retur_part/jenisRetur');?>",
               type:"POST",
               data:"jenis_retur="+jenis_retur,
               cache:false,
               success:function(html){
                if (jenis_retur == 'po_checker') {
                   var data = html.split("|");
                   $('#no_sj')[0].options.length = 0;
                  document.getElementById("no_sj").innerHTML=data[0];
                 // document.getElementById("part").innerHTML=data[1];
                }
                else if(jenis_retur =='po_aksesoris')
                {
                   var data = html.split("|");
                  $('#no_sj')[0].options.length = 0;
                  document.getElementById("no_sj").innerHTML=data[0];
                  //document.getElementById("part").innerHTML=data[1];
               }
             }
          });
  }
  function jenis_ReturNoSJ()
  {
    var value = {jenis_retur :$('#jenis_retur').val(),
                no_sj :$('#no_sj').val()
    }
    $.ajax({
               url:"<?php echo site_url('h1/retur_part/jenisRetur');?>",
               type:"POST",
               data:value,
               cache:false,
               success:function(html){
                var data = html.split("|");
                document.getElementById("part").innerHTML=data[1];
             }
          });
  }
</script>