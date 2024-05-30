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
          <a href="dealer/stock_opname">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/stock_opname/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                              
                <div class="form-group">
                  <input type="hidden" name="mode" value="input">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Stock Opname</label>
                  <div class="col-sm-3">
                    <input type="text" required class="form-control"  id="tanggal"  placeholder="Tanggal Stock Opname" name="tgl_stock_opname">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-5">
                    <input type="text" required class="form-control" placeholder="Keterangan" name="keterangan">                    
                  </div>
                </div> 
                <div class="form-group">
                  <input type="hidden" name="mode" value="input">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-5">
                    <select class="select2 form-control" name="tipe" id="tipe">
                      <option>--Pilih--</option>
                      <option value="all">Semua Tipe</option>
                      <?php $tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan ")->result(); ?>
                      <?php foreach ($tipe as $tp): ?>
                        <option value="<?php echo $tp->id_tipe_kendaraan ?>"><?php echo $tp->tipe_ahm ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="col-sm-3">
                    <button class="btn btn-primary" type="button" onclick="pilih_tipe()"><i class="fa fa-plus"></i></button>
                  </div>
                </div>  
                <div class="form-group">
                  <div class="col-sm-2"></div>
                  <div class="col-sm-10">
                      <table class="table table-bordered" style="width: 30%">
                        <div id="tampil_tipe"></div>
                  </table>            
                  </div>
                </div> 
                 <p align="center">
                   <button class="btn btn-primary btn-flat" type="button" onclick="show_data()">Generate</button>
                 </p>
                 <br>            
                <div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>                      
                  <span id="tampil_data"></span>   
                </div>  
              </div>
          </div>
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
    </div><!-- /.box -->
  </form>

    <?php 
    }elseif($set=='detail'){
      $row = $dt_stock_op->row();
    ?>
    
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/stock_opname">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/stock_opname/save" method="post" enctype="multipart/form-data">
              <div class="box-body">                              
                <div class="form-group">
                  <input type="hidden" name="mode" value="input">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Stock Opname</label>
                  <div class="col-sm-3">
                    <input type="text" disabled class="form-control"  id="tanggal"  placeholder="Tanggal Stock Opname" name="tgl_stock_opname" value="<?php echo $row->tanggal ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-5">
                    <input type="text" disabled class="form-control" placeholder="Keterangan" name="keterangan" value="<?php echo $row->keterangan ?>">                    
                  </div>
                </div>           
                <div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>                      <br>
                  <table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>      
      <th>No</th>
      <th>Tipe</th>              
      <th>Warna</th>              
      <th>Kode Item</th>
      <th>No Mesin</th>
      <th>No Rangka</th>        
      <th>Aksi</th>       
    </tr>
  </thead>
 
  <tbody>                    
    <?php   
    $no = 1;
    $x = 0;
    foreach($dt_->result() as $isi) {  
      if ($isi->checked==1) {
        $cek='checked';
      }else{
        $cek='';
      }
    ?>

      <tr>
        <td align="center" style="width: 4%"><?php echo $no ?></td>
        <td><?php echo $isi->tipe_ahm ?></td>
        <td><?php echo $isi->warna ?></td>
        <td><?php echo $isi->id_item ?></td>
        <td><?php echo $isi->nomesin ?></td>
        <td><?php echo $isi->no_rangka ?></td>
        <td align="center" style="width: 4%">
          <input type="checkbox" disabled <?php echo $cek ?>>
        </td>
      </tr>
     <?php $no++;$x++;
      }
    ?>
    <input type="hidden" name="jum" value="<?php echo $no-=1; ?>">
  </tbody>
</table>        
                </div>  
              </div>
          </div>
        </div>        
      </div><!-- /.box-body -->
      
    </div><!-- /.box -->
  </form>

   
    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/stock_opname/add">
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
              <th>Tgl Stock Opname</th>              
              <th>Keterangan</th>            
              <th>Qty On Hand</th>
              <th>Qty Stock Opname</th>              
              <th>Selisih</th>              
              <th>Action</th>              
            </tr>
          </thead>
          <tbody> 
          <?php $no=1; foreach ($dt_stock_op->result() as $stok): ?>
              <tr>
                <td><?php echo $no ?></td>
                <td><?php echo $stok->tanggal ?></td>
                <td><?php echo $stok->keterangan ?></td>
                <td><?php echo $stok->qty_on_hand ?></td>
                <td><?php echo $stok->qty_stock_opname ?></td>
                <td><?php echo $stok->selisih ?></td>
                <td align="center">
                  <a href="<?php echo site_url('dealer/stock_opname/detail?id='.$stok->id_stock_opname) ?>" class="btn btn-xs btn-warning btn-flat" ><i class="fa fa-eye"></i></a>
                  <a href="<?php echo site_url('dealer/stock_opname/edit?id='.$stok->id_stock_opname) ?>" class="btn btn-xs btn-primary btn-flat" ><i class="fa fa-pencil"></i></a>
                </td>
              </tr>
          <?php endforeach ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="edit"){
      $row =$stock_opname;
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/stock_opname">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <div id="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/stock_opname/update" method="post" enctype="multipart/form-data">
              <div class="box-body">                              
                <div class="form-group">
                  <input type="hidden" name="mode" value="input">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Stock Opname</label>
                  <div class="col-sm-3">
                    <input type="text" required class="form-control"  id="tanggal"  placeholder="Tanggal Stock Opname" name="tgl_stock_opname" value="<?= $row->tanggal?>">
                    <input type="hidden" name="id_stock_opname" value="<?php echo $row->id_stock_opname ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-5">
                    <input type="text" required class="form-control" placeholder="Keterangan" name="keterangan" value="<?php echo $row->keterangan ?>">                    
                  </div>
                </div>    
                <div class="form-group">
                  <button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>          <br>            
                     <table id="example2" class="table myTable1 table-bordered table-hover">
                      <thead>
                        <tr>      
                          <th>No</th>
                          <th>Tipe</th>              
                          <th>Warna</th>              
                          <th>Kode Item</th>
                          <th>No Mesin</th>
                          <th>No Rangka</th>        
                          <th>Aksi</th>       
                        </tr>
                      </thead>
                     
                      <tbody>                    
                        <?php   
                        $no = 1;
                        $x = 0;
                        foreach($dt_->result() as $isi) {  
                          if ($isi->checked==1) {
                            $cek='checked';
                          }else{
                            $cek='';
                          }
                        ?>

                          <tr>
                            <td align="center" style="width: 4%"><?php echo $no ?></td>
                            <td><?php echo $isi->tipe_ahm ?></td>
                            <td><?php echo $isi->warna ?></td>
                            <td><?php echo $isi->id_item ?></td>
                            <td><?php echo $isi->nomesin ?></td>
                            <td><?php echo $isi->no_rangka ?></td>
                            <td align="center" style="width: 4%">
  <input type="checkbox" name="check_<?= $x ?>" value="<?php echo $isi->no_mesin ?>" <?php echo $cek ?> >
          <input type="hidden" name="no_mesin[]" value="<?php echo $isi->no_mesin ?>" >
          <input type="hidden" name="id_detail[]" value="<?php echo $isi->id_detail ?>" >
                            </td>
                          </tr>
                         <?php $no++;$x++;
                          }
                        ?>
                        <input type="hidden" name="jum" value="<?php echo $no-=1; ?>">
                      </tbody>
                    </table>           
                </div>  
              </div>
          </div>
        </div>        
      </div><!-- /.box-body -->
      <div class="box-footer">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-10">
          <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>              
        </div>
      </div><!-- /.box-footer -->
    </div><!-- /.box -->
  </form>

    <?php 
    }?>
  </section>
</div>



<script type="text/javascript">

function pilih_tipe(){
  var tipe = $('#tipe').val();
   $.ajax({
       beforeSend: function() { $('#loading-status').show(); },
       url:"<?php echo site_url('dealer/stock_opname/pilih_tipe');?>",
       type:"POST",
       data:"tipe="+tipe,
       cache:false,
       success:function(html){
          $('#loading-status').hide();
          $('#tampil_tipe').html(html);
          //window.location.replace("<?php echo site_url('h1/stock_opname/add') ?>");
       },
       statusCode: {
    500: function() {
      $('#loading-status').hide();
      alert('Terjadi Kesalahan Saat Menambahkan Data');
    }
  }
  });
}

function show_data(){    
  $("#tampil_data").show();
  var tipe  = document.getElementById("tipe").value;  
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "tipe="+tipe;                           
     xhr.open("POST", "dealer/stock_opname/show_data", true); 
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
    }   
}

</script>