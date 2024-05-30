<?php 
function bln($b){
  $bulan=$bl=$month=$b;
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
  if($_GET['v'] == 'v'  OR $_GET['v'] == 'a'){
?>
<body onload="kirim_data_niguri_v()">
<?php 
  }elseif($_GET['v'] == 'e'){
?>
<body onload="kirim_data_niguri()">
<?php
}
}else{ ?>
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
    <li class="">Pembelian</li>
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
          <a href="h1/niguri">
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
            <form class="form-horizontal" action="h1/niguri/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Niguri</label>
                  <div class="col-sm-2">
                    <input type="text" required class="form-control"  id="id_niguri" readonly placeholder="ID Niguri" name="id_niguri">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                  </div>
                
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="bulan" id="bulan" onchange="cek_bulan()">
                      <option value="<?php echo date("m") ?>"><?php echo bln(date("m")) ?></option>
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="tahun" id="tahun" onchange="cek_bulan()">
                      <option><?php echo date("Y") ?></option>
                      <?php 
                      $y = date("Y");
                      for ($i=$y - 5; $i <= $y + 10; $i++) { 
                        echo "<option>$i</option>";
                      }
                      ?>                          
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control  flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>             
                </div>

                <hr>                
                <div class="form-group">
                  <table id="myTable" class="table myTable1 order-list" border="0">
                    <thead>
                      <tr>
                        <th width="10%">ID Item</th>
                        <th width="20%">Tipe</th>
                        <th width="14%">Warna</th>                        
                        <th width="15%"><div align="right">Jenis</a></th>
                        <th width="7%">M-1 <font color="red">[ <span id="lm1"></span> ]</font></th>
                        <th width="7%">M <font color="red">[ <span id="lm"></span> ]</font></th>
                        <th width="7%">Fix <font color="blue">[ <span id="lfix"></span> ]</font></th>
                        <th width="7%">T1 <font color="red">[ <span id="lt1"></span> ]</font></th>
                        <th width="9%">T2 <font color="red">[ <span id="lt2"></span> ]</font></th>  
                        <th width="7%">Action</th>                      
                      </tr>
                    </thead> 
                  </table>                  
                  
                  <span id="tampil_niguri"></span>                                                                                  
                  
                  <table id="myTable" class="table myt order-list" border="0">     
                    <tbody>                      
                      <tr>
                        <td width="10%" rowspan="4">
                          <!--input id="id_item" readonly type="text" data-toggle="modal" data-target="#Itemmodal" name="id_item" class="form-control isi" placeholder="ID Item"-->
                          <input id="id_item" readonly type="text" onclick="tampil_id()" name="id_item" class="form-control isi" placeholder="ID Item">
                        </td>
                        <td width="20%" rowspan="4">
                          <input type="text" id="tipe" data-toggle="modal" data-target="#Itemmodal" placeholder="Tipe" class="form-control isi" name="tipe" readonly>
                        </td>
                        <td width="14%" rowspan="4">
                          <input type="text" id="warna" data-toggle="modal" data-target="#Itemmodal" placeholder="Warna" class="form-control isi" name="warna" readonly>
                        </td>
                        <td width="15%" align="right">
                          AHM Dist to MD
                        </td>
                        <td width="7%">
                          <input type="text" id="a_m1" readonly onkeypress="return number_only(event)" class="form-control isi" placeholder="M-1" name="m-1">
                        </td>
                        <td width="7%">
                          <input type="text" id="a_m" readonly onkeypress="return number_only(event)" class="form-control isi" placeholder="M" name="m">
                        </td>
                        <td width="7%">
                          <input type="text"  id="a_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Fix" name="fix">
                        </td>
                        <td width="7%">
                          <input type="text"  id="a_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="T1" name="t1">
                        </td>
                        <td width="7%">
                          <input type="text"  id="a_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="T2" name="t2">                          
                        </td>
                        <td width="7%">
                          <button type="button" onClick="load_niguri()" class="btn btn-sm bg-maroon btn-flat"><i class="fa fa-refresh"></i> Load</button>                          
                        </td>                        
                      </tr>
                      <tr>                        
                        <td width="15%" align="right">
                          Retail Sales
                        </td>
                        <td width="7%">
                          <input type="text"  id="b_m1" readonly onkeypress="return number_only(event)" class="form-control isi" placeholder="M-1" name="m-1">
                        </td>
                        <td width="7%">
                          <input type="text"  id="b_m" onkeypress="return number_only(event)" class="form-control isi" placeholder="M" name="m">
                        </td>
                        <td width="7%">
                          <input type="text"  id="b_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Fix" name="fix">
                        </td>
                        <td width="7%">
                          <input type="text"  id="b_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="T1" name="t1">
                        </td>
                        <td width="7%">
                          <input type="text"  id="b_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="T2" name="t2">                          
                        </td>
                        <td width="7%" rowspan="3">
                          <button type="button" onClick="simpan_niguri()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>                          
                        </td>                        
                      </tr>
                      <tr>
                        <td width="15%" align="right">
                          Total Stock
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="c_m1" onkeypress="return number_only(event)" class="form-control isi" placeholder="M-1" name="m-1">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="c_m" onkeypress="return number_only(event)" class="form-control isi" placeholder="M" name="m">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="c_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Fix" name="fix">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="c_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="T1" name="t1">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="c_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="T2" name="t2">                          
                        </td>
                      </tr>
                      <tr>
                        <td width="15%" align="right">
                          Total Stock Day
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="d_m1" onkeypress="return number_only(event)" class="form-control isi" placeholder="M-1" name="m-1">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="d_m" onkeypress="return number_only(event)" class="form-control isi" placeholder="M" name="m">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="d_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Fix" name="fix">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="d_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="T1" name="t1">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="d_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="T2" name="t2">                          
                        </td>
                      </tr>

                    </tbody>                        
                  </table>

                  <span id="total_niguri"></span>                                                                                  

                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    

    <?php 
    }elseif($set=="edit"){
      $row = $dt_niguri->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/niguri">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>
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
            <form class="form-horizontal" action="h1/niguri/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_niguri ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Niguri</label>
                  <div class="col-sm-2">
                    <input type="text" required readonly value="<?php echo $row->id_niguri ?>" class="form-control"  id="id_niguri" placeholder="ID Niguri" name="id_niguri">
                  </div>
                
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="bulan" id="bulan" readonly>
                      <option value="<?php echo $row->bulan ?>"><?php echo bln($row->bulan) ?></option>                      
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="tahun" readonly>
                      <option><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-6">
                    <input type="text" value="<?php echo $row->ket ?>" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>             
                </div>

                <hr>                
                <div class="form-group">
                  <table id="myTable" class="table myTable1 order-list" border="0">
                    <thead>
                     <tr>
                        <th width="10%">ID Item</th>
                        <th width="20%">Tipe</th>
                        <th width="14%">Warna</th>
                        <th width="15%"><div align="right">Jenis</a></th>
                        <th width="7%">M-1 <font color="red">[ <span id="lm1"></span> ]</font></th>
                        <th width="7%">M <font color="red">[ <span id="lm"></span> ]</font></th>
                        <th width="7%">Fix <font color="blue">[ <span id="lfix"></span> ]</font></th>
                        <th width="7%">T1 <font color="red">[ <span id="lt1"></span> ]</font></th>
                        <th width="7%">T2 <font color="red">[ <span id="lt2"></span> ]</font></th>  
                        <th width="7%">Action</th>                                              
                      </tr>
                    </thead> 
                  </table>                  
                  
                  <span id="tampil_niguri"></span>                                                                                  
                  
                  <table id="myTable" class="table myt order-list" border="0">     
                    <tbody>                      
                      <tr>
                        <td width="10%" rowspan="4">
                          <input id="id_item" readonly type="text" data-toggle="modal" data-target="#Itemmodal" name="id_item" class="form-control isi" placeholder="ID Item">
                        </td>
                        <td width="20%" rowspan="4">
                          <input type="text" id="tipe" data-toggle="modal" data-target="#Itemmodal" placeholder="Tipe" class="form-control isi" name="tipe" readonly>
                        </td>
                        <td width="14%" rowspan="4">
                          <input type="text" id="warna" data-toggle="modal" data-target="#Itemmodal" placeholder="Warna" class="form-control isi" name="warna" readonly>
                        </td>
                        <td width="15%" align="right">
                          AHM Dist to MD
                        </td>
                        <td width="7%">
                          <input type="text" id="a_m1" onkeypress="return number_only(event)" class="form-control isi" placeholder="M -1" name="m-1">
                        </td>
                        <td width="7%">
                          <input type="text" id="a_m" onkeypress="return number_only(event)" class="form-control isi" placeholder="M" name="m">
                        </td>
                        <td width="7%">
                          <input type="text" id="a_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Fix" name="fix">
                        </td>
                        <td width="7%">
                          <input type="text" id="a_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="T1" name="t1">
                        </td>
                        <td width="9%">
                          <input type="text" id="a_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="T2" name="t2">                          
                        </td>
                        <td width="7%">
                          <button type="button" onClick="load_niguri()" class="btn btn-sm bg-maroon btn-flat"><i class="fa fa-refresh"></i> Load</button>                          
                        </td>                        
                      </tr>
                      <tr>                        
                        <td width="15%" align="right">
                          Retail Sales
                        </td>
                        <td width="7%">
                          <input type="text" id="b_m1" onkeypress="return number_only(event)" class="form-control isi" placeholder="M-1" name="m-1">
                        </td>
                        <td width="7%">
                          <input type="text" id="b_m" onkeypress="return number_only(event)" class="form-control isi" placeholder="M" name="m">
                        </td>
                        <td width="7%">
                          <input type="text" id="b_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Fix" name="fix">
                        </td>
                        <td width="7%">
                          <input type="text" id="b_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="T1" name="t1">
                        </td>
                        <td width="7%">
                          <input type="text" id="b_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="T2" name="t2">                          
                        </td>
                        <td width="9%" rowspan="3">
                          <button type="button" onClick="simpan_niguri()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>                          
                        </td>                        
                      </tr>
                      <tr>
                        <td width="15%" align="right">
                          Total Stock
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="c_m1" onkeypress="return number_only(event)" class="form-control isi" placeholder="M-1" name="m-1">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="c_m" onkeypress="return number_only(event)" class="form-control isi" placeholder="M" name="m">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="c_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Fix" name="fix">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="c_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="T1" name="t1">
                        </td>
                        <td width="9%">
                          <input type="text" readonly id="c_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="T2" name="t2">                          
                        </td>
                      </tr>
                      <tr>
                        <td width="15%" align="right">
                          Total Stock Day
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="d_m1" onkeypress="return number_only(event)" class="form-control isi" placeholder="M-1" name="m-1">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="d_m" onkeypress="return number_only(event)" class="form-control isi" placeholder="M" name="m">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="d_fix" onkeypress="return number_only(event)" class="form-control isi" placeholder="Fix" name="fix">
                        </td>
                        <td width="7%">
                          <input type="text" readonly id="d_t1" onkeypress="return number_only(event)" class="form-control isi" placeholder="T1" name="t1">
                        </td>
                        <td width="9%">
                          <input type="text" readonly id="d_t2" onkeypress="return number_only(event)" class="form-control isi" placeholder="T2" name="t2">                          
                        </td>
                      </tr>

                    </tbody>                        
                  </table>

                  <span id="total_niguri"></span>                                                                                  


                </div>                                              
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Reset</button>                                
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_niguri->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/niguri">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>
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
            <form class="form-horizontal" action="h1/niguri/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_niguri ?>" />
              <input type="hidden" name="mode" value="<?php echo $_GET['v'] ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Niguri</label>
                  <div class="col-sm-2">
                    <input type="text" required readonly value="<?php echo $row->id_niguri ?>" class="form-control"  id="id_niguri" placeholder="ID Niguri" name="id_niguri">
                  </div>
                
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="bulan" id="bulan" readonly>
                      <option value="<?php echo $row->bulan ?>"><?php echo bln($row->bulan) ?></option>                      
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="tahun" readonly>
                      <option><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-6">
                    <input type="text" readonly value="<?php echo $row->ket ?>" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>             
                </div>

                <hr>                
                <div class="form-group">
                  <table id="myTable" class="table myTable1 order-list" border="0">
                    <thead>
                     <tr>
                        <th width="10%">ID Item</th>
                        <th width="20%">Tipe</th>
                        <th width="14%">Warna</th>
                        <th width="15%"><div align="right">Jenis</a></th>
                        <th width="7%">M-1 <font color="red">[ <span id="lm1"></span> ]</font></th>
                        <th width="7%">M <font color="red">[ <span id="lm"></span> ]</font></th>
                        <th width="7%">Fix <font color="blue">[ <span id="lfix"></span> ]</font></th>
                        <th width="7%">T1 <font color="red">[ <span id="lt1"></span> ]</font></th>
                        <th width="9%">T2 <font color="red">[ <span id="lt2"></span> ]</font></th>  
                      </tr>
                    </thead> 
                  </table>                  
                  <span id="tampil_niguri"></span>                                                                                  
                  <span id="total_niguri"></span>                                                                                  
                </div>                                              
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="approval"){
      $row = $dt_niguri->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/niguri">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>
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
            <form class="form-horizontal" action="h1/niguri/save_approval" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_niguri ?>" />
              <input type="hidden" name="mode" value="<?php echo $_GET['v'] ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Niguri</label>
                  <div class="col-sm-2">
                    <input type="text" required readonly value="<?php echo $row->id_niguri ?>" class="form-control"  id="id_niguri" placeholder="ID Niguri" name="id_niguri">
                  </div>
                
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="bulan" id="bulan" readonly>
                      <option value="<?php echo $row->bulan ?>"><?php echo $row->bulan ?></option>                      
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="tahun" readonly>
                      <option><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-6">
                    <input type="text" readonly value="<?php echo $row->ket ?>" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <?php 
                      if($row->active=='1'){
                      ?>
                      <input type="checkbox" class="flat-red" name="active" value="1" checked>
                      <?php }else{ ?>
                      <input type="checkbox" class="flat-red" name="active" value="1">                      
                      <?php } ?>
                      Active
                    </div>
                  </div>             
                </div>

                <hr>                
                <div class="form-group">
                  <table id="myTable" class="table myTable1 order-list" border="0">
                    <thead>
                     <tr>
                        <th width="10%">ID Item</th>
                        <th width="20%">Tipe</th>
                        <th width="14%">Warna</th>
                        <th width="15%"><div align="right">Jenis</a></th>
                        <th width="7%">M-1 <font color="red">[ <span id="lm1"></span> ]</font></th>
                        <th width="7%">M <font color="red">[ <span id="lm"></span> ]</font></th>
                        <th width="7%">Fix <font color="blue">[ <span id="lfix"></span> ]</font></th>
                        <th width="7%">T1 <font color="red">[ <span id="lt1"></span> ]</font></th>
                        <th width="9%">T2 <font color="red">[ <span id="lt2"></span> ]</font></th>  
                      </tr>
                    </thead> 
                  </table>                  
                  <span id="tampil_niguri"></span>                                                                                  
                  <span id="total_niguri"></span>                                                                                  
                </div>                                              
              </div>            
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to approve all data?')" name="process" value="approve" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Approve</button>                
                  <button type="submit" onclick="return confirm('Are you sure to reject all data?')" name="process" value="reject" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject</button>                                  
                </div>
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
          <a href="h1/niguri/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>ID Niguri</th>              
              <th>Bulan</th>
              <th>Tahun</th>
              <th>Keterangan</th>
              <th>Status</th>
              <th width="18%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_niguri->result() as $row) {       
            if($row->active=='1') $active = "<i class='glyphicon glyphicon-ok'></i>";
                else $active = "";
            if($row->status_niguri=='input'){
              $tombol = "<a data-toggle=\"tooltip\" title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-sm btn-flat\" href=\"h1/niguri/delete?id=$row->id_niguri\"><i class=\"fa fa-trash-o\"></i></a>
                  <a data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-sm btn-flat\" href=\"h1/niguri/edit?id=$row->id_niguri&v=e\"><i class=\"fa fa-edit\"></i></a>
                  <a data-toggle=\"tooltip\" title=\"Approval\" class=\"btn btn-success btn-sm btn-flat\" href=\"h1/niguri/approval?id=$row->id_niguri&v=a\"><i class=\"fa fa-check\"></i></a>
                  <a data-toggle=\"tooltip\" title=\"Download\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/niguri/download?id=$row->id_niguri&v=a\"><i class=\"fa fa-download\"></i></a>";
              $status = "<span class='label label-warning'>$row->status_niguri</span>";
            }elseif ($row->status_niguri=='approved' ) {
                $tombol="<a data-toggle=\"tooltip\" title=\"Download\" class=\"btn btn-warning btn-sm btn-flat\" href=\"h1/niguri/download?id=$row->id_niguri&v=a\"><i class=\"fa fa-download\"></i></a>";
              $status = "<span class='label label-success'>$row->status_niguri</span>";
                
            }
            else{
              $status = "<span class='label label-success'>$row->status_niguri</span>";
              $tombol = "";
            }                 

          echo "          
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/niguri/detail?id=$row->id_niguri&v=v'>
                  $row->id_niguri
                </a>
              </td>
              <td>"; echo bln($row->bulan)."</td>
              <td>$row->tahun</td>
              <td>$row->ket</td>
              <td>$status</td>                                          
              <td>";
              echo $tombol;
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


<div class="modal fade" id="Itemmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>ID Item</th>
              <th>Tipe Kendaraan</th>                                    
              <th>Warna</th>                                               
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_item->result() as $ve2) {
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->id_item</td>
              <td>$ve2->tipe_ahm</td>
              <td>$ve2->warna</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->id_item; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>           
            </tr>
            <?php
            $no++;
          }
          ?>
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade"  width="850px" id="modal_niguri">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Data Niguri</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" action="h1/niguri/edit_niguri" method="post" enctype="multipart/form-data">            
            <input type="hidden" class="form-control" id="id_niguri_detail" name="id_niguri_detail">
            <input type="hidden" class="form-control" id="id_niguri" name="id_niguri">
            <div class="box-body">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">ID Item</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="id_item" placeholder="ID Item" name="id_item" readonly>
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="warna" placeholder="Warna" name="warna" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tipe_ahm" placeholder="Tipe Kendaraan" name="tipe_ahm" readonly>
                </div>                
              </div>                 
              <div class="form-group">
                <table class="myTable1">
                  <thead>
                    <tr>
                      <th align="center" width="25%">Jenis</th>
                      <th >M-1</th>
                      <th align="center">M</th>
                      <th align="center">Fix</th>
                      <th align="center">T1</th>
                      <th align="center">T2</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>AHM Dist to MD</td>
                      <td><input type="text" class="form-control isi" name="am1" id="am1"></td>
                      <td><input type="text" class="form-control isi" name="am" id="am"></td>
                      <td><input type="text" class="form-control isi" name="afix" id="afix"></td>
                      <td><input type="text" class="form-control isi" name="at1" id="at1"></td>
                      <td><input type="text" class="form-control isi" name="at2" id="at2"></td>
                    </tr>
                    <tr>
                      <td>Retail Sales</td>
                      <td><input type="text" class="form-control isi" name="bm1" id="bm1"></td>
                      <td><input type="text" class="form-control isi" name="bm" id="bm"></td>
                      <td><input type="text" class="form-control isi" name="bfix" id="bfix"></td>
                      <td><input type="text" class="form-control isi" name="bt1" id="bt1"></td>
                      <td><input type="text" class="form-control isi" name="bt2" id="bt2"></td>
                    </tr>
                    <tr>
                      <td>Total Stock</td>
                      <td><input type="text" class="form-control isi" name="cm1" readonly id="cm1"></td>
                      <td><input type="text" class="form-control isi" name="cm" readonly id="cm"></td>
                      <td><input type="text" class="form-control isi" name="cfix" readonly id="fix"></td>
                      <td><input type="text" class="form-control isi" name="ct1" readonly id="ct1"></td>
                      <td><input type="text" class="form-control isi" name="ct2" readonly id="ct2"></td>
                    </tr>
                    <tr>
                      <td>Total Stock Days</td>
                      <td><input type="text" class="form-control isi" name="dm1" readonly id="dm1"></td>
                      <td><input type="text" class="form-control isi" name="dm" readonly id="dm"></td>
                      <td><input type="text" class="form-control isi" name="dfix" readonly id="dfix"></td>
                      <td><input type="text" class="form-control isi" name="dt1" readonly id="dt1"></td>
                      <td><input type="text" class="form-control isi" name="dt2" readonly id="dt2"></td>
                    </tr>
                  </tbody>
                </table>
              </div>             
            </div><!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" name="s_process" value="edit" class="btn btn-info">Update</button>
              <a href="adm/mapel">
                <button type="button" data-dismiss="modal" class="btn btn-default pull-right">Cancel</button>                
              </a>
            </div><!-- /.box-footer -->
          </form>
      </div>      
    </div>
  </div>
</div>


<script type="text/javascript">

function tampil_id(){
  var id_niguri = document.getElementById("id_niguri").value; 
  $.ajax({
      url : "<?php echo site_url('h1/niguri/cari_niguri')?>",
      type:"POST",
      data:"id_niguri="+id_niguri,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#Itemmodal").modal("show");
      }        
  }) 
}

function auto(){
  var niguri_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('h1/niguri/cari_id')?>",
      type:"POST",
      data:"niguri="+niguri_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_niguri").val(data[0]);
        //$("#lm").val("08");
        kirim_data_niguri();                        
        cek_bulan();
      }        
  })
}
function cek_bulan(){  
  var bulan = $("#bulan").val();
  var a1 = parseInt(bulan) - 2;
  var a2 = parseInt(bulan) - 1;
  var a3 = parseInt(bulan);
  var a4 = parseInt(bulan) + 1;
  var a5 = parseInt(bulan) + 2;
  if(a1 == "-1"){
    a1 = "11";
  }else if(a1 == "0"){
    a1 = "12";
  }
  if(a2 == "0"){
    a2 = "12";
  }
  if(a5 == "14"){
    a5 = "2";
  }else if(a5 == "13"){
    a5 = "1";
  }
  if(a4 == "13"){
    a4 = "1";
  }

  document.getElementById("lm1").innerHTML = a1; 
  document.getElementById("lm").innerHTML = a2; 
  document.getElementById("lfix").innerHTML = a3; 
  document.getElementById("lt1").innerHTML = a4; 
  document.getElementById("lt2").innerHTML = a5; 

  

}
function cek_isi(){
  var lm1_js  = document.getElementById("lm1").innerHTML;                       
  var lm_js   = document.getElementById("lm").innerHTML;                       
  var lfix_js = document.getElementById("lfix").innerHTML;                       
  var lt1_js  = document.getElementById("lt1").innerHTML;                       
  var lt2_js  = document.getElementById("lt2").innerHTML;                       
  var tahun_js  = document.getElementById("tahun").value;                       
  $.ajax({
      url: "<?php echo site_url('h1/niguri/cek_isi')?>",
      type:"POST",
      data:"lm1="+lm1_js+"&lm="+lm_js+"&lfix="+lfix_js+"&lt1="+lt1_js+"&lt2="+lt2_js+"&tahun="+tahun_js,
      cache:false,
      success:function(msg){                
        data=msg.split("|");
        if(data[0]=="ok"){          
          $("#a_m1").val(data[1]);                            
          $("#b_m1").val(data[2]);                            
          $("#c_m1").val(data[3]);                            
          $("#d_m1").val(data[4]);

          $("#a_m").val(data[5]);                            
          $("#b_m").val(data[6]);                            
          $("#c_m").val(data[7]);                            
          $("#d_m").val(data[8]);

          $("#a_fix").val(data[9]);                            
          $("#b_fix").val(data[10]);                            
          $("#c_fix").val(data[11]);                            
          $("#d_fix").val(data[12]);

          $("#a_t1").val(data[13]);                            
          $("#b_t1").val(data[14]);                            
          $("#c_t1").val(data[15]);                            
          $("#d_t1").val(data[16]);

          $("#a_t2").val(data[17]);                            
          $("#b_t2").val(data[18]);                            
          $("#c_t2").val(data[19]);                            
          $("#d_t2").val(data[20]);                            
        }else{
          alert(data[0]);
        }
      } 
  })
}

function cancel_tr(){
  var id_niguri_js=document.getElementById("id_niguri").value; 
  if (confirm("Are you sure to cancel this transaction...?") == true) {
      $.ajax({
        url : "<?php echo site_url('h1/niguri/cancel_niguri')?>",
        type:"POST",
        data:"id_niguri="+id_niguri_js,   
        cache:false,   
        success: function(msg){ 
          window.location.reload();
        }        
    })
  }else{
    return false;
  }  
}
function chooseitem(id_item){
  document.getElementById("id_item").value = id_item; 
  cek_item();
  $("#Itemmodal").modal("hide");
}
function cek_item(){
  var id_item = $("#id_item").val();                       
  var bulan   = $("#bulan").val();                       
  var tahun   = $("#tahun").val();                       
  $.ajax({
      url: "<?php echo site_url('h1/niguri/cek_item')?>",
      type:"POST",
      data:"id_item="+id_item+"&bulan="+bulan+"&tahun="+tahun,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#id_item").val(data[1]);                
            $("#tipe").val(data[2]);                
            $("#warna").val(data[3]);
            $("#a_m1").val(data[4]);
            $("#a_m").val(data[5]);
            $("#a_fix").val(data[6]);
            $("#a_t1").val(data[7]);
            $("#a_t2").val(data[8]);
            $("#b_m1").val(data[9]);
            $("#b_m").val(data[10]);
            $("#b_fix").val(data[11]);
            $("#b_t1").val(data[12]);
            $("#b_t2").val(data[13]);
            //cek_niguri();
          }else{
            alert(data[0]);
          }
      } 
  })
}
function hide_niguri(){
    $("#tampil_niguri").hide();
}
function kirim_data_niguri(){  
  cek_bulan();  
  $("#tampil_niguri").show();
  var id_niguri = document.getElementById("id_niguri").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_niguri="+id_niguri;                           
     xhr.open("POST", "h1/niguri/t_niguri", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_niguri").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }

   kirim_data_niguri_total();

}
function kirim_data_niguri_total(){    
  $("#total_niguri").show();
  var id_niguri = document.getElementById("id_niguri").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_niguri="+id_niguri;                           
     xhr.open("POST", "h1/niguri/t_niguri_total", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("total_niguri").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }

}

function kirim_data_niguri_v(){    
  $("#tampil_niguri").show();
  cek_bulan();
  var id_niguri = document.getElementById("id_niguri").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_niguri="+id_niguri;                           
     xhr.open("POST", "h1/niguri/t_niguri_v", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_niguri").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }

   cek_bulan();
   kirim_data_niguri_total_v();
}
function kirim_data_niguri_total_v(){    
  $("#total_niguri").show();
  var id_niguri = document.getElementById("id_niguri").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_niguri="+id_niguri;                           
     xhr.open("POST", "h1/niguri/t_niguri_total_v", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("total_niguri").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    }

}

function cek_niguri(){  
  var id_niguri           = document.getElementById("id_niguri").value;   
  var bulan               = document.getElementById("bulan").value;   
  var tahun               = document.getElementById("tahun").value;   
  var id_item             = document.getElementById("id_item").value;   
  var a_m1                = document.getElementById("a_m1").value;   
  var a_m                 = document.getElementById("a_m").value;   
  var a_fix               = document.getElementById("a_fix").value;   
  var a_t1                = document.getElementById("a_t1").value;   
  var a_t2                = document.getElementById("a_t2").value;   
  var b_m1                = document.getElementById("b_m1").value;   
  var b_m                 = document.getElementById("b_m").value;   
  var b_fix               = document.getElementById("b_fix").value;   
  var b_t1                = document.getElementById("b_t1").value;   
  var b_t2                = document.getElementById("b_t2").value;   
  //alert(b_t2);
  $.ajax({
    url : "<?php echo site_url('h1/niguri/cek_niguri_fix')?>",
    type:"POST",
    data:"id_niguri="+id_niguri+"&tahun="+tahun+"&bulan="+bulan+"&id_item="+id_item+"&a_m1="+a_m1+"&a_m="+a_m+"&a_fix="+a_fix+"&a_t1="+a_t1+"&a_t2="+a_t2+"&b_m1="+b_m1+"&b_m="+b_m+"&b_fix="+b_fix+"&b_t1="+b_t1+"&b_t2="+b_t2,
    cache:false,
    success:function(msg){
      data = msg.split("|");
      if(data[0] == "ok"){        
        $("#c_m1").val(data[1]);                        
        $("#c_m").val(data[2]);                        
        $("#c_fix").val(data[3]);                        
        $("#c_t1").val(data[4]);                        
        $("#c_t2").val(data[5]);                        
        $("#d_m1").val(data[6]);                        
        $("#d_m").val(data[7]);                        
        $("#d_fix").val(data[8]);                        
        $("#d_t1").val(data[9]);                        
        $("#d_t2").val(data[10]);                        
      }      
    }
  })
}
function load_niguri(){  
  cek_niguri();  
}
function simpan_niguri(){
  save_niguri();
  kirim_data_niguri();
  kosong();
}
function save_niguri(){
    var bulan               = document.getElementById("bulan").value;   
    var tahun               = document.getElementById("tahun").value;   
    var id_niguri           = document.getElementById("id_niguri").value;   
    var id_item             = document.getElementById("id_item").value;   
    var a_m1                = document.getElementById("a_m1").value;   
    var a_m                 = document.getElementById("a_m").value;   
    var a_fix               = document.getElementById("a_fix").value;   
    var a_t1                = document.getElementById("a_t1").value;   
    var a_t2                = document.getElementById("a_t2").value;   
    var b_m1                = document.getElementById("b_m1").value;   
    var b_m                 = document.getElementById("b_m").value;   
    var b_fix               = document.getElementById("b_fix").value;   
    var b_t1                = document.getElementById("b_t1").value;   
    var b_t2                = document.getElementById("b_t2").value;   
    var c_m1                = document.getElementById("c_m1").value;   
    var c_m                 = document.getElementById("c_m").value;   
    var c_fix               = document.getElementById("c_fix").value;   
    var c_t1                = document.getElementById("c_t1").value;   
    var c_t2                = document.getElementById("c_t2").value;   
    var d_m1                = document.getElementById("d_m1").value;   
    var d_m                 = document.getElementById("d_m").value;   
    var d_fix               = document.getElementById("d_fix").value;   
    var d_t1                = document.getElementById("d_t1").value;   
    var d_t2                = document.getElementById("d_t2").value;   
    //alert(active);
    if (id_niguri=="" || id_item=="") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('h1/niguri/save_niguri')?>",
            type:"POST",
            data:"id_niguri="+id_niguri+"&id_item="+id_item+"&a_m1="+a_m1+"&a_m="+a_m+"&a_fix="+a_fix+"&a_t1="+a_t1+"&a_t2="+a_t2+
                "&b_m1="+b_m1+"&b_m="+b_m+"&b_fix="+b_fix+"&b_t1="+b_t1+"&b_t2="+b_t2+
                "&c_m1="+c_m1+"&c_m="+c_m+"&c_fix="+c_fix+"&c_t1="+c_t1+"&c_t2="+c_t2+
                "&d_m1="+d_m1+"&d_m="+d_m+"&d_fix="+d_fix+"&d_t1="+d_t1+"&d_t2="+d_t2+"&bulan="+bulan+"&tahun="+tahun,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){                            
                  kirim_data_niguri();
                }else{
                  alert(data[0]);                  
                }
            }
        })    
    }
}
function kosong(args){
  $("#id_item").val("");
  $("#warna").val("");   
  $("#tipe").val("");   
  $("#retail").val("");   
  $("#outlock_m").val("");   
  $("#outlock_m1").val("");   
  $("#outlock_m2").val("");   
  $("#stock_days_m2").val("");   
  $("#retail").val("");   
  $("#a_m1").val("");   
  $("#a_m").val("");   
  $("#a_fix").val("");   
  $("#a_t1").val("");   
  $("#a_t2").val("");   
  $("#b_m1").val("");   
  $("#b_m").val("");   
  $("#b_fix").val("");   
  $("#b_t1").val("");   
  $("#b_t2").val("");   
  $("#c_m1").val("");   
  $("#c_m").val("");   
  $("#c_fix").val("");   
  $("#c_t1").val("");   
  $("#c_t2").val("");   
  $("#d_m1").val("");   
  $("#d_m").val("");   
  $("#d_fix").val("");   
  $("#d_t1").val("");   
  $("#d_t2").val("");   
}
function hapus_niguri(a,b){ 
    var id_niguri_detail  = a;   
    var id_item   = b;       
    $.ajax({
        url : "<?php echo site_url('h1/niguri/delete_niguri')?>",
        type:"POST",
        data:"id_niguri_detail="+id_niguri_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_data_niguri();
            }
        }
    })
}
function edit_niguri(id){    
  //Ajax Load data from ajax
  $.ajax({
      url : "<?php echo site_url('h1/niguri/cari_data')?>",
      type:"POST",
      data:"id="+id,      
      success: function(msg)
      { 
          data=msg.split("|");
          $('[name="id_item"]').val(data[0]);          
          $('[name="tipe_ahm"]').val(data[1]);                    
          $('[name="warna"]').val(data[2]);                    
          $('[name="am1"]').val(data[3]);                    
          $('[name="am"]').val(data[4]);                    
          $('[name="afix"]').val(data[5]);                    
          $('[name="at1"]').val(data[6]);                    
          $('[name="at2"]').val(data[7]);                              
          $('[name="bm1"]').val(data[8]);                    
          $('[name="bm"]').val(data[9]);                    
          $('[name="bfix"]').val(data[10]);                    
          $('[name="bt1"]').val(data[11]);                    
          $('[name="bt2"]').val(data[12]);                              
          $('[name="cm1"]').val(data[13]);                    
          $('[name="cm"]').val(data[14]);                    
          $('[name="cfix"]').val(data[15]);                    
          $('[name="ct1"]').val(data[16]);                    
          $('[name="ct2"]').val(data[17]);                              
          $('[name="dm1"]').val(data[18]);                    
          $('[name="dm"]').val(data[19]);                    
          $('[name="dfix"]').val(data[20]);                    
          $('[name="dt1"]').val(data[21]);                    
          $('[name="dt2"]').val(data[22]);                              
          $('[name="id_niguri_detail"]').val(data[23]);                              
          $('[name="id_niguri"]').val(data[24]);                              
          $('#modal_niguri').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Data Niguri'); // Set title to Bootstrap modal title

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error get data from ajax');
      }
  });
}
</script>