<style>
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
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H3</li>
    <li class="">Logistik</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="pemenuhan"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/po_checker">
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
            <form class="form-horizontal" action="h3/po_checker/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="no_po" readonly value="<?php echo $id_tr_po_checker ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Checker</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_checker" id="tgl_po" readonly value="<?php echo $tgl ?>" class="form-control">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_po" readonly value="<?php echo date('Y-m-d') ?>" class="form-control">                    
                  </div>
              
                </div>                                  
                <div class="form-group">
                  <table id="example" class="table table-bordered table-hover">
                    <thead>
                      <tr>              
                        <th width="5%">No</th>
                        <th>Kode Part</th>              
                        <th>Nama Part</th>
                        <th>QTY Order</th>    
                        <th>Qty Pemenuhan</th>        
                      </tr>
                    </thead>
                    <tbody>            
                      <?php 
                      $no=1;
                      foreach ($dt_detail->result() as $row) {                     
                       if (!!$row->jum) {
                          echo "
                        <tr>
                          <td>$no</td>
                          <td>$row->id_part</td>
                          <td>$row->nama_part</td>
                          <td>$row->jum</td>                          
                          <td>

                            <input type='hidden' name='id_part_$no' value='$row->id_part'>
                            <input type='hidden' name='qty_order_$no' value='$row->jum'>
                            <input type='hidden' name='no' value='$no'>
                            <input type='text' class='form-control isi' name='qty_pemenuhan_$no' value=''>
                          </td>                          
                        </tr>
                        ";
                        $no++;
                       }
                        
                      }
                      ?>
                    </tbody>
                  </table>

                </div>                                                                                                                                   
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                 <?php if ($no>1): ?>
                                   <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button> 
                                 <?php endif ?>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php 
    }elseif($set=="approve"){
    	$header = $header->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/po_checker">
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
            <form class="form-horizontal" action="h3/po_checker/approve" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="no_po" readonly value="<?php echo $header->no_po ?>" class="form-control">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Checker</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_checker" id="tgl_po" readonly value="<?php echo $header->tgl_checker ?>" class="form-control">                    
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tgl_po" readonly value="<?php echo $header->tgl_po ?>" class="form-control">                    
                  </div>
              
                </div>                                  
                <div class="form-group">
                  <table id="example" class="table table-bordered table-hover">
                    <thead>
                      <tr>              
                        <th width="5%">No</th>
                        <th>Kode Part</th>              
                        <th>Nama Part</th>
                        <th>QTY Order</th>    
                        <th>Qty Pemenuhan</th>        
                      </tr>
                    </thead>
                    <tbody>            
                      <?php 
                      $no=1;
                      foreach ($detail->result() as $row) {                     
                          echo "
                        <tr>
                          <td>$no</td>
                          <td>$row->id_part</td>
                          <td>$row->nama_part</td>
                          <td>$row->qty_order</td>                          
                          <td>

                            <input type='hidden' name='id_part_$no' value='$row->id_part'>
                            <input type='hidden' name='qty_order_$no' value='$row->qty_order'>
                            <input type='hidden' name='id_detail_$no' value='$row->id_detail'>
                            <input type='hidden' name='no' value='$no'>
                            <input type='text' class='form-control isi' name='qty_pemenuhan_$no' value='$row->qty_pemenuhan'>
                          </td>                          
                        </tr>
                        ";
                        $no++;
                      }
                      ?>
                    </tbody>
                  </table>

                </div>                                                                                                                                   
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                 <?php if ($no>1): ?>
                                   <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="submit_approve" value="save" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Approve All</button>
                  <!--<button type="reset" class="btn btn-danger btn-flat"><i class="fa fa-refresh"></i> Reject All</button> --->
                                 <?php endif ?>                
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
           <!--<a href="h3/po_checker/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>                 -->  
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
              <th>No.PO</th>              
              <th>Tgl PO</th>              
              <th>Tgl Checker</th>              
              <th>No. Surat Jalan</th>              
              <th>Tgl Surat Jalan</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbosdy>            
          <?php 
          $no=1; 
          foreach($dt->result() as $row) {
          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_po</td>
              <td>$row->tgl_po</td>
              <td>$row->tgl_checker</td>
              <td>$row->no_sj</td>              
              <td>";  
              if ($row->status=='approved') {
                echo "$row->tgl_sj";
              }
              echo"</td>              
              <td>$row->status</td>              
              <td>";
                if (!$row->status) { ?>
                  <a href="h3/po_checker/pemenuhan?tgl=<?php echo $row->tgl_checker ?>" class="btn btn-primary btn-sm btn-flat btn-xs"><i class="fa fa-list"></i> Pemenuhan</a>
                <?php }
                elseif ($row->status=='input') { ?>
                	<a href="<?php echo site_url('h3/po_checker/approve?po='.$row->no_po) ?>" class="btn btn-success btn-sm btn-flat btn-xs">Approve</a>
               <?php }
               elseif ($row->status=='approved') { ?>
               <a href="<?php echo site_url('h3/po_checker/cetak_sj/'.$row->no_po) ?>" class="btn btn-warning btn-sm btn-flat btn-xs">Cetak Surat Jalan</a>
               <?php }
              echo "</td>";
              ?>              
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
  var id = 1;
  $.ajax({
      url : "<?php echo site_url('h3/po_checker/cari_id')?>",
      type:"POST",
      data:"id="+id,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_po_checker").val(data[0]);              
      }        
  })
}
function cari_bundling(){    
  $("#tampil_bundling").show();
  var id_paket_bundling = document.getElementById("id_paket_bundling").value;   
  var qty_paket = document.getElementById("qty_paket").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_paket_bundling="+id_paket_bundling+"&qty_paket="+qty_paket;                           
     xhr.open("POST", "h3/po_checker/t_bundling", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_bundling").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
</script>