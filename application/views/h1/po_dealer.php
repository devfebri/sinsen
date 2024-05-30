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
.mb-10{
  margin-bottom: 2px;
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
          <a href="h1/po_dealer">
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

            $id_user = $this->session->userdata("id_user");
            $sql = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_karyawan_dealer ON ms_user.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
              INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
              WHERE ms_user.id_user = '$id_user'")->row();
                
        ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/po_dealer/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4"> -->
                    <input type="hidden" required class="form-control"  id="id_po" readonly placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="new">
                  <!-- </div> -->
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <!-- <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $jenis ?>" class="form-control">                     -->
                    <select class="form-control" id="jenis_po" name="jenis_po" onchange="cek_jenis()">
                      <!-- <option value="PO Reguler">PO Reguler</option> -->
                      <option value="PO Additional">PO Additional</option>
                    </select>
                  </div>
                <!-- </div> 
                <div class="form-group"> -->
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <!-- <input type="text" readonly class="form-control" value="<?php echo $sql->nama_dealer ?>" placeholder="Dealer" name="delaer">
                    <input type="hidden" name="id_dealer" value="<?php echo $sql->id_dealer ?>"> -->
                    <select class="form-control select2" name="id_dealer" id="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      $dealer = $this->m_admin->getSortCond("ms_dealer","nama_dealer","ASC");
                      foreach ($dealer->result() as $isi) {
                        echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md | $isi->nama_dealer</option>";
                      }
                      ?>
                    </select>
                    
                  </div>
                </div>                                                                                     
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" id="bulan" onchange="cek_bulan()">
                      <option value="<?php echo date("m") ?>"><?php echo bln() ?></option>
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
                  <div class="col-sm-4">
                    <select class="form-control" name="tahun" id="tahun" onchange="cek_bulan()">
                      <option><?php echo date("Y") ?></option>
                      <?php 
                      $y = date("Y");
                      for ($i=$y - 10; $i <= $y + 10; $i++) { 
                        echo "<option>$i</option>";
                      }
                      ?>                          
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>             
                </div>

                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_po"></span>                                                                                  
                  
                  
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
      $row = $dt_po->row();

      $id_dealer = $row->id_dealer;
      $sql = $this->db->query("SELECT * FROM ms_dealer WHERE ms_dealer.id_dealer = '$id_dealer'")->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/po_dealer">
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
            <form class="form-horizontal" action="h1/po_dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_po ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->id_po ?>" readonly class="form-control"  id="id_po" placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="edit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $row->jenis_po ?>" class="form-control">                    
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $sql->nama_dealer ?>" placeholder="Dealer" name="delaer">
                    <input type="hidden" name="id_dealer" id="id_dealer" value="<?php echo $sql->id_dealer ?>">
                  </div>
                </div>                                                                                                                                                         
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" readonly id="bulan">
                      <option value="<?php echo $row->bulan ?>"><?php echo $row->bulan ?></option>                                            
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="tahun" id="tahun">
                      <option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->ket ?>" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>             
                </div>

                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_po"></span>                                                                                  
                  
                  
                </div>                                                 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Reset</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_po->row();

      $id_dealer = $row->id_dealer;
      $sql = $this->db->query("SELECT * FROM ms_dealer WHERE ms_dealer.id_dealer = '$id_dealer'")->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/po_dealer">
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
            <form class="form-horizontal" action="h1/po_dealer/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_po ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->id_po ?>" readonly class="form-control"  id="id_po" placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="detail">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $row->jenis_po ?>" class="form-control">                    
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $sql->nama_dealer ?>" placeholder="Dealer" name="delaer">
                    <input type="hidden" name="id_dealer" value="<?php echo $sql->id_dealer ?>">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PO</label>
                  <div class="col-sm-4">
                   <input type="text" class="form-control" value="<?= $row->tgl ?>" readonly>
                  </div>
                </div>                                                                                                                                                         
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" readonly id="bulan">
                      <option value="<?php echo $row->bulan ?>"><?php echo $row->bulan ?></option>                                            
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="tahun" id="tahun">
                      <option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->ket ?>" readonly class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                <div class="form-group">                
                  <label for="field-1" class="col-sm-2 control-label">Status</label>            
                  <div class="col-sm-2">
                    <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                      <input type="checkbox" class="form-control flat-red" name="active" value="1" checked>
                      Active
                    </div>
                  </div>             
                </div>

                <hr>                
                <div class="form-group">
                  <span id="tampil_po"></span>                                                                                  
                </div>                                                 
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
          <div class="box-tools pull-left">
        <a href="h1/po_dealer/add?type=add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>

          <a href="h1/po_dealer?jenispo=additional" class="btn bg-green btn-flat margin"> PO Additional</a>
          <a href="h1/po_dealer?jenispo=regular" class="btn bg-primary btn-flat margin"> PO Regular</a>
          <a href="h1/po_dealer?jenispo=indent" class="btn bg-yellow btn-flat margin"> PO Indent</a>
          <label id="myLabel" hidden><?php 
            if(isset($_GET['jenispo'])) { 
              echo $_GET['jenispo'];
              $geturlpo=$_GET['jenispo'];
            } else{
              echo 'additional';
            }
            ?>
        </label>    
          </div>  
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
        <table id="table_po" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>Dealer</th>
              <th>No.PO</th>              
              <th>Jenis PO</th>
              <th>Bulan</th>              
              <th>Tahun</th>
              <th>Status</th>
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>   
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->



<?php
    }elseif($set=="processed"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/po_dealer">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>          
          
          <!--a href="h1/po_dealer/download(20171000004)">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Download</button>
          </a-->          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
          <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="master/kelurahan/save" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">PO</label>
                  <div class="col-sm-4">
                    <input name="po_number" type="text" class="form-control" autocomplete="off" value="<?= $row->po_number ?>" readonly />
                    
                  </div>
                </div>                                                                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<?php }elseif ($set=='form'){
$disabled = '';
$readonly = '';
$form     = '';
$po_type  = isset($row->po_type)?$row->po_type:$po_type;
if ($po_type=='reg') {
  $po_type_full='REGULAR';
}else{
  $po_type_full='ADDITIONAL';
}
if ($mode=='detail') {
  $disabled='disabled';
}
if ($mode=='insert') {
  $form ='save_po_by_md';
}
if ($mode=='edit') {
  $form = 'save_edit';
}
 ?>
 <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
         <a href="h1/po_dealer">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>          
           </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>

    <?php } ?>
  })
</script>
<div class="row">
  <div class="col-md-12">
    <form id="form_" class="form-horizontal" action="h1/po_dealer/<?= $form ?>" method="post" enctype="multipart/form-data">
      <div class="box-body">     
        <div class="col-md-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <div class="col-md-7">
                <h3 class="box-title"><b><?= $po_type_full ?> PO</b></h3><br>
                <h3 class="box-title"><b><?= $po_number ?></b></h3>
              </div>
              <div class="col-md-5">
                <h3 class="box-title" style="color: #3C8DBC;text-align: right;">
                  <?php 
                      $tgl = date('d');
                      $deadline = $set_md->deadline_po_dealer;
                      if ($tgl>$deadline) {
                        $bulan=date('m')+2;
                        $tahun = date('Y');
                        if ($bulan>12) {
                          $bulan=2;
                          $tahun=$tahun+1;
                        }
                      }else{
                        $bulan = date('m')+1;
                        $tahun=date('Y');
                        if ($bulan>12) {
                          $bulan=1;
                          $tahun=$tahun+1;
                        }
                      }
                      $bulan = isset($row->po_period_m)?$row->po_period_m:$bulan;
                      $tahun = isset($row->po_period_y)?$row->po_period_y:$tahun;
                   ?>
                  <?php if ($po_type=='reg'): ?>
                    <b><?= strtoupper(bulan_pjg($bulan)) ?> <?= $tahun ?></b>
                  <?php endif ?>
                </h3>
                  <?php if ($po_type=='add'): ?>
                    <input type="text" class="form-control datepicker" name="tgl" autocomplete="off" placeholder="Pilih Tanggal PO" required id="tgl" value="<?= isset($row)?$row->tgl:'' ?>" <?= $disabled ?>><br>
                  <?php endif ?>
                
              </div>
              <div class="col-md-offset-7 col-md-5">
                <select name="id_dealer" class="form-control select2">
                      <?php 
                      $dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active=1 AND h1=1");
                      if ($dealer->num_rows()>0) {
                        echo '<option value="">-Pilih Dealer-</option>';
                        foreach ($dealer->result() as $rs) {
                          echo '<option value="'.$rs->id_dealer.'">'.$rs->kode_dealer_md.' | '.$rs->nama_dealer.'</option>';
                        }
                      }
                      ?>
                    </select>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
                <?php 
                  $status ='input';
                  $btn_tipe='warning';
                  $status_show = 'draft';
                  if (isset($row)) {
                    $status = $row->status;
                    if ($status=='input') {
                      $btn_tipe = 'warning';
                      $status_show='draft';
                    }
                    if ($status=='submitted') {
                      $btn_tipe = 'info';
                      $status_show = 'submitted';
                    }
                    if ($status=='approved') {
                      $btn_tipe = 'primary';
                      $status_show = 'processed';
                    }
                  }
                 ?>
                <button type="button" class="btn btn-<?=$btn_tipe?>" style="width: 100%;text-align: left" disabled><b><?= strtoupper($status_show) ?></b></button>
            </div>
          </div>
        </div>
        <button style="margin-bottom: 20px" class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
      
          <table v-if="po_type=='reg'" class="table table-bordered table-striped">
            <thead>
              <th  width="18%">Tipe Unit</th>
              <th width="18%">Warna</th>
              <th>Current Stock</th>
              <th>Monthly Sales</th>
              <th>PO T1 Last Period</th>
              <th>PO T2 Last Period</th>
              <th>PO Fix (<?= $set_md->po_fix_dealer ?>%)</th>
              <th>PO T1 (<?= $set_md->po_t1_dealer ?>%)</th>
              <th>PO T2 (Free)</th>
              <th>Kuantitas Indent</th>
              <!-- <th width="15%">Total Harga</th> -->
              <th v-if="mode!='detail'">Action</th>
            </thead>
            <tbody>
              <tr v-for="(dtl, index) of details">
                <td>{{dtl.id_tipe_kendaraan}} | {{dtl.tipe_unit}}</td>
                <td>{{dtl.id_warna}} | {{dtl.warna}}</td>
                <td>{{dtl.current_stock}}</td>
                <td>{{dtl.monthly_sale}}</td>
                <td>{{dtl.po_t1_last}}</td>
                <td>{{dtl.po_t2_last}}</td>
                <td><input type="text" @change="form_.cekPoFix(index)" class="form-control isi" v-model="dtl.po_fix" <?= $disabled ?>></td>
                <td><input type="text" @change="form_.cekPoT1(index)" class="form-control isi" v-model="dtl.qty_po_t1" <?= $disabled ?>></td>
                <td><input type="text" class="form-control isi" v-model="dtl.qty_po_t2" <?= $disabled ?>></td>
                <td>{{dtl.qty_indent}}
                  <input type="hidden" v-model="total(dtl)">
                </td>
                <!-- <td><vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="total(dtl)" 
                          v-bind:minus="false" :empty-value="0" readonly separator="."/> -->
                <!-- </td> -->
                <td v-if="mode!='detail'">
                  <button class="btn btn-flat btn-danger btn-xs" @click.prevent="form_.delDetails(index)"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr v-if="mode!='detail'">
                <td>
                  <select id="id_tipe_kendaraan" class="form-control select2" onchange="form_.getWarna()">
                    <?php if ($tipe_unit->num_rows()>0): ?>
                      <option value="">--choose--</option>
                      <?php foreach ($tipe_unit->result() as $tu):
                          $warna = $this->db->query("SELECT ms_warna.id_warna,ms_warna.warna from ms_item 
                                    inner join ms_warna on ms_item.id_warna =ms_warna.id_warna
                                    WHERE id_tipe_kendaraan='$tu->id_tipe_kendaraan'
                                    GROUP BY ms_item.id_warna
                                    ORDER BY ms_warna.warna ASC")->result()
                      ?>
                        <option value="<?= $tu->id_tipe_kendaraan ?>" data-tipe_unit="<?= $tu->tipe_ahm ?>" data-warna='<?= json_encode($warna) ?>'><?= $tu->id_tipe_kendaraan.' | '.$tu->tipe_ahm ?></option>
                      <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </td>
                <td><select onchange="form_.getDetail()" class="form-control select2" id="id_warna"></select></td>
                <td>{{detail.current_stock}}</td>
                <td>{{detail.monthly_sale}}</td>
                <td>{{detail.po_t1_last}}</td>
                <td>{{detail.po_t2_last}}</td>
                <td><input type="text" onchange="form_.cekPoFix()" class="form-control isi" v-model="detail.po_fix"></td>
                <td><input type="text" onchange="form_.cekPoT1()" class="form-control isi" v-model="detail.qty_po_t1"></td>
                <td><input type="text" class="form-control isi" v-model="detail.qty_po_t2"></td>
                <td>{{detail.qty_indent}}
                  <input type="hidden" v-model="totDetail(detail)">
                </td>
               <!--  <td><vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="totDetail(detail)" 
                          v-bind:minus="false" :empty-value="0" readonly separator="."/></td> -->
                <td v-if="mode!='detail'">
                  <button type="button" onclick="form_.addDetails()" class="btn btn-primary btn-flat btn-xs">
                    <i class="fa fa-plus"></i>
                  </button>
                </td>
              </tr>
            </tfoot>
          </table>

          <table v-if="po_type=='add'" class="table table-bordered table-striped">
            <thead>
              <th>ID Item</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th width="15%">Qty Order</th>
              <th v-if="mode!='detail'">Action</th>
            </thead>
             <tbody>
              <tr v-for="(dtl, index) of details">
                <td>{{dtl.id_item}}</td>
                <td>{{dtl.tipe_unit}}</td>
                <td>{{dtl.warna}}</td>
                <td><input type="text" class="form-control isi" v-model="dtl.po_fix" <?= $disabled ?>></td>
                <td v-if="mode!='detail'">
                  <button class="btn btn-flat btn-danger btn-xs" @click.prevent="form_.delDetails(index)"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
            <tfoot v-if="mode!='detail'">
              <tr>
                <td><input type="text" @click.prevent="form_.showModalItem()" class="form-control isi" readonly v-model="detail.id_item"></td>
                <td><input type="text" class="form-control isi" v-model="detail.tipe_ahm" readonly></td>
                <td><input type="text" class="form-control isi" v-model="detail.warna" readonly></td>
                <td><input type="text" class="form-control isi" v-model="detail.po_fix"></td>
                <td v-if="mode!='detail'">
                  <button type="button" onclick="form_.addDetails()" class="btn btn-primary btn-flat btn-xs">
                    <i class="fa fa-plus"></i>
                  </button>
                </td>
              </tr>
            </tfoot>
            <tfoot>
              <tr>
                <td colspan="3" style="text-align: right;">Total</td>
                <td colspan="2">{{ total_qty }}</td>
              </tr>
            </tfoot>
          </table>
          <hr>
            <div v-if="mode!='detail'" class="col-md-12" style="text-align: center;">
              <button type="button" class="btn btn-primary" @click.prevent="form_.saveForm('submitted')"><i class="fa fa-save"></i> SAVE</button>
            </div>
      </div>
    </form>
  </div>
</div>
<script>

// function cekTglPO() {
// var tgl_po = $('#tgl').val();
//   console.log(tgl_po);
//   var tgl = new Date();
//   va
//   if (tgl_hari_ini<new Date(tgl_po)) {
//     console.log('ss')
//     alert('tanggal tidak boleh lebih kecil dari hari ini !');
//     $('#tgl_po').val('')
//     return false;
//   }
// }
   var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        po_type :'<?= $po_type ?>',
        detail:{
          id_tipe_kendaraan : '',
          tipe_unit : '',
          id_warna :'',
          warna:'',
          current_stock : '',
          monthly_sale : '',
          po_t1_last : '',
          po_t2_last : '',
          po_fix : '',
          qty_po_t1 : '',
          qty_po_t2 : '',
          qty_indent : '',
          harga : '',
          total_harga:'',
          min_po_fix:'',
          max_po_fix:'',
          min_po_t1:'',
          max_po_t1:''
        },
        total_qty: 0,
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
      methods: {
        total: function (unit) {
            total = unit.harga*(parseInt(unit.po_fix)+parseInt(unit.qty_indent));
            ppn = total * (10/100);
            total = total + ppn;
            return total;
        },
        cekPoFix: function (index=null) {
          if (index==null) {
            var po_fix_dealer = <?= $set_md->po_fix_dealer ?>;
            var max_po_fix = 0;
            var min_po_fix = 0;
            if (this.detail.po_t1_last>0) {
              var max_po_fix = Math.floor(parseInt(this.detail.po_t1_last) + parseFloat(this.detail.po_t1_last * (po_fix_dealer/100)));
              var min_po_fix = Math.ceil(parseInt(this.detail.po_t1_last) - parseFloat(this.detail.po_t1_last * (po_fix_dealer/100)));
            }
            this.detail.min_po_fix = min_po_fix;
            this.detail.max_po_fix = max_po_fix;

            if (parseFloat(max_po_fix)>0) {
              if (parseInt(this.detail.po_fix)>parseFloat(max_po_fix)) {
                alert('PO Fix melebihi batas maksimal!');
                this.detail.po_fix='';
              }
              else if (parseInt(this.detail.po_fix)<parseFloat(min_po_fix)) {
                alert('PO Fix kurang dari batas minimal !');
                this.detail.po_fix='';
              }
            }
          }else{
            var max_po_fix = this.details[index].max_po_fix;
            var min_po_fix = this.details[index].min_po_fix;

            if (parseFloat(max_po_fix)>0) {
              if (parseInt(this.details[index].po_fix)>parseFloat(max_po_fix)) {
                alert('PO Fix melebihi batas maksimal!');
                this.details[index].po_fix='';
              }
              else if (parseInt(this.details[index].po_fix)<parseFloat(min_po_fix)) {
                alert('PO Fix kurang dari batas minimal !');
                this.details[index].po_fix='';
              }
            }
          }
          // console.log(this.detail);
        },
         cekPoT1: function (index=null) {
          if (index==null) {
            var po_t1_dealer = <?= $set_md->po_t1_dealer ?>;
            var max_po_t1 = 0;
            var min_po_t1 = 0;
            if (this.detail.po_t2_last>0) {
              var max_po_t1 = Math.floor(parseInt(this.detail.po_t2_last) + parseFloat(this.detail.po_t2_last * (po_t1_dealer/100)));
              var min_po_t1 = Math.ceil(parseInt(this.detail.po_t2_last) - parseFloat(this.detail.po_t2_last * (po_t1_dealer/100)));
            }
            this.detail.min_po_t1 = min_po_t1;
            this.detail.max_po_t1 = max_po_t1;

            // max > 101.6 << 101
            // min > 90.2 >> 91
            if (parseFloat(max_po_t1)>0) {
              if (parseInt(this.detail.qty_po_t1)>parseFloat(max_po_t1)) {
                alert('PO T1 melebihi batas maksimal!');
                this.detail.qty_po_t1='';
              }
              else if (parseInt(this.detail.qty_po_t1)<parseFloat(min_po_t1)) {
                alert('PO T1 kurang dari batas minimal !');
                this.detail.qty_po_t1='';
              }
            }
          }else{
            var max_po_t1 = this.details[index].max_po_t1;
            var min_po_t1 = this.details[index].min_po_t1;
            if (parseFloat(max_po_t1)>0) {
              if (parseInt(this.details[index].qty_po_t1)>parseFloat(max_po_t1)) {
                alert('PO Fix melebihi batas maksimal!');
                this.details[index].qty_po_t1='';
              }
              else if (parseInt(this.details[index].qty_po_t1)<parseFloat(min_po_t1)) {
                alert('PO Fix kurang dari batas minimal !');
                this.details[index].qty_po_t1='';
              }
            }
          }
        },
        getWarna: function() {
          var element   = $('#id_tipe_kendaraan').find('option:selected'); 
          var id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
          if (id_tipe_kendaraan=='' || id_tipe_kendaraan==null) {
            $('#id_warna').html('');
            return false;
          }
          var warnas    = JSON.parse(element.attr("data-warna")); 
          var tipe_unit = element.attr("data-tipe_unit");
          form_.detail.tipe_unit = tipe_unit; 
          form_.detail.id_tipe_kendaraan = $('#id_tipe_kendaraan').val(); 
          $('#id_warna').html('');
            if (warnas.length>0) {
              $('#id_warna').append($('<option>').text('--choose--').attr('value', ''));
            }
          $.each(warnas, function(i, value) {
            $('#id_warna').append($('<option>').text(warnas[i].id_warna+' | '+warnas[i].warna).attr({'value':warnas[i].id_warna,'warna':warnas[i].warna}));

          });
        },
        getDetail: function() {
          var element           = $('#id_warna').find('option:selected'); 
          var warna             = element.attr("warna");
          form_.detail.warna    = warna; 
          form_.detail.id_warna = $('#id_warna').val(); 

          values = {id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),
                    id_warna:$('#id_warna').val()
                   }
          $.ajax({
            url:"<?php echo site_url('h1/po_dealer/getDetail');?>",
            type:"POST",
            data:values,
            cache:false,
            dataType:'JSON',
            success:function(response){
              form_.detail.current_stock = response.current_stock;
              form_.detail.monthly_sale  = response.monthly_sale;
              form_.detail.po_t1_last    = response.po_t1_last;
              form_.detail.po_t2_last    = response.po_t2_last;
              form_.detail.qty_indent    = response.qty_indent;
              form_.detail.harga         = response.harga;
              // console.log(form_.detail)
            }
          });
        },
        clearDetail: function(){
          $('#id_tipe_kendaraan').val('').trigger('change');
          // $('#id_warna').html('');
          this.detail={
            id_tipe_kendaraan : '',
            tipe_unit : '',
            id_warna :'',
            warna:'',
            current_stock : '',
            monthly_sale : '',
            po_t1_last : '',
            po_t2_last : '',
            po_fix : '',
            qty_po_t1 : '',
            qty_po_t2 : '',
            qty_indent : '',
            harga : '',
            total_harga:'',
            min_po_fix:'',
            max_po_fix:'',
            min_po_t1:'',
            max_po_t1:'',
            id_item:''
          }  
        },
        showModalItem : function() {
          // $('#tbl_part').DataTable().ajax.reload();
          $('.modalItem').modal('show');
        },
        addDetails : function(){
          // console.log(this.detail);
          if (this.detail.id_tipe_kendaraan=='' || this.detail.id_warna==''|| this.detail.po_fix==''|| this.detail.qty_po_t1==''||this.detail.qty_po_t2=='') 
          {
            alert('Isi data dengan lengkap !');
            return false;
          }
          for(dtl of this.details)
          {
            if (this.detail.id_tipe_kendaraan==dtl.id_tipe_kendaraan && this.detail.id_warna==dtl.id_warna) {
              alert('Item sudah dipilih !')
              return false;
            }
          }
          this.details.push(this.detail);
          this.total_qty = parseInt(this.total_qty) + parseInt(this.detail.po_fix);
          console.log(this.total_qty);
          this.clearDetail();
        },
  
        delDetails: function(index){
            this.details.splice(index, 1);
        },
        saveForm:function(save_to){
          
          if (this.details.length==0) {
            alert('Belum ada unit yang dipilih !');
            return false;
          }else{
            if (this.po_type=='add') {
              var tgl=$('#tgl').val();
              if (tgl=='') {
                alert('Tanggal PO Additional belum dipilih !');
                return false;
              }
            }else{
              var val_confirm = confirm('Are you sure ?');
              if (val_confirm==false) {
                return false;
              }
            }
          }
          var values ={detail:form_.details};
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          values['save_to'] = save_to;
          values['po_type'] = '<?= $po_type ?>';
          values['po_number'] = '<?= $po_number ?>';

          $.ajax({
            beforeSend: function() {
              $('.btnSubmit').attr('disabled',true);
              },
            url:"<?= base_url('h1/po_dealer/'.$form);?>",
            type:"POST",
            data: values,
            cache:false,
            dataType:'JSON',
            success:function(respon){
              if (respon.status=='sukses') {
                window.location = "<?= base_url('h1/po_dealer') ?>";
              }
            },
            error:function(){
              alert("failure");
              $('.btnSubmit').attr('disabled',false);

            },
            statusCode: {
              500: function() { 
                alert('fail');
                $('#submitBtn').attr('disabled',false);

              }
            }
          });
        },
        totDetail:function(detail) {
          po_fix     = detail.po_fix==''?0:detail.po_fix;
          qty_indent = detail.qty_indent==''?0:detail.qty_indent;
          total      = detail.harga * (parseInt(po_fix)+parseInt(qty_indent));
          ppn = total *(10/100);
          this.detail.total_harga = total+ppn;
          total = total+ppn;
          return total;
        },
      },
      watch:{
        detail:function () {
          // alert('dd');
        }
      },
      computed: {
        // totDetail:function(detail) {
        //   po_fix     = detail.po_fix==''?0:detail.po_fix;
        //   qty_indent = detail.qty_indent==''?0:detail.qty_indent;
        //   total      = detail.harga * (parseInt(po_fix)+parseInt(qty_indent));
        //   ppn = total *(10/100);
        //   this.detail.total_harga = total+ppn;
        //   return total;
        // },
      },
  });
Vue.component('date-picker',{
    template: '<input type="text" v-datepicker class="form-control isi_combo" :value="value" @input="update($event.target.value)">',
    directives: {
        datepicker: {
            inserted (el, binding, vNode) {
                $(el).datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    todayHighlight:false,
                }).on('changeDate',function(e){
                    vNode.context.$emit('input', e.format(0))
                })
            }
        }
    },
    props: ['value'],
    methods: {
        update (v){
            this.$emit('input', v)
        }
    }
})

</script>
<div class="modal fade modalItem" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Item</h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_item" style="width: 100%">
                  <thead>
                  <tr>
                      <th>ID Item</th>
                      <th>Tipe Kendaraan</th>
                      <th>Warna</th>
                      <th>Action</th>
                  </tr>
                  </thead>
              </table>
              <script>
                  function pilihItem(item)
                  {
                    form_.detail={
                      id_tipe_kendaraan:item.id_tipe_kendaraan,
                      id_warna:item.id_warna,
                      warna:item.warna,
                      tipe_ahm:item.tipe_ahm,
                      tipe_unit:item.tipe_ahm,
                      id_item:item.id_item,
                    }
                    console.log(form_.detail)
                  }
                  $(document).ready(function(){
                      $('#tbl_item').DataTable({
                          processing: true,
                          serverSide: true,
                          "language": {                
                                  "infoFiltered": ""
                              },
                          order: [],
                          ajax: {
                              url: "<?= base_url('h1/po_dealer/fetch_item') ?>",
                              dataSrc: "data",
                              data: function ( d ) {
                                    // d.kode_item     = $('#kode_item').val();
                                    return d;
                                },
                              type: "POST"
                          },
                          "columnDefs":[  
                      // { "targets":[4],"orderable":false},
                      { "targets":[3],"className":'text-center'}, 
                      // { "targets":[4], "searchable": false } 
                 ]
                      });
                  });
              </script>
      </div>
    </div>
  </div>
</div>
<?php } ?>
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
          if (isset($dt_item)) {
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
          }
          ?>
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>
<div class="modal fade"  width="850px" id="modal_po_add">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Data PO</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" action="h1/po_dealer/edit_po_add" method="post" enctype="multipart/form-data">            
            <input type="hidden" class="form-control" id="id_po_detail" name="id_po_detail">
            <input type="hidden" class="form-control" id="id_po" name="id_po">
            <input type="hidden" class="form-control" id="mode" name="mode">
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
                <label for="inputEmail3" class="col-sm-2 control-label">Qty Order</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="qty_order" placeholder="Qty Order" name="qty_order">
                </div>                
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


<div class="modal fade"  width="850px" id="modal_po_reg">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Data PO</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" action="h1/po_dealer/edit_po_reg" method="post" enctype="multipart/form-data">            
            <input type="hidden" class="form-control" id="id_po_detail" name="id_po_detail">
            <input type="hidden" class="form-control" id="id_po" name="id_po">
            <input type="hidden" class="form-control" id="mode" name="mode">
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
                      <th align="center">Qty PO Fix</th>
                      <th align="center">Qty PO T1</th>
                      <th align="center">Qty PO T2</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" class="form-control isi" name="qty_po_fix"></td>
                      <td><input type="text" class="form-control isi" name="qty_po_t1"></td>
                      <td><input type="text" class="form-control isi" name="qty_po_t2"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div><!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" name="s_process" value="edit" class="btn btn-info">Update</button>
              <button type="button" data-dismiss="modal" class="btn btn-default pull-right">Cancel</button>                
            </div><!-- /.box-footer -->
          </form>
      </div>      
    </div>
  </div>
</div>

<script>
  $( document ).ready(function() {
    var labelText = $("#myLabel").text()
    // alert(labelText);
   tabless = $('#table_po').DataTable({
	      "scrollX": true,
        "processing": true, 
        "bDestroy": true,
        "serverSide": true, 
        "order": [],
        "ajax": {
          "url": "<?php  echo site_url('h1/po_dealer/fetch_data_po_dealer_datatables?jenispo=')?>"+labelText,
            "type": "POST"
        },  
              
        "columnDefs": [
        {
            "targets": [ 0,5 ],
            "orderable": false, 
        },
        ],
        });
        tabless.render();
});
</script>


<script type="text/javascript">
function cek_jenis(){
  var jenis_po = document.getElementById("jenis_po").value;
  if(jenis_po == 'PO Reguler'){
    kirim_data_po_reg();
  }else if(jenis_po == 'PO Additional' || jenis_po == 'PO Indent'){
    kirim_data_po_add();
  }
}
function cek_bulan(){
  var bulan = document.getElementById("bulan").value;
  var tahun = document.getElementById("tahun").value;
  //$("#jenis_po").val(bulan);
  $.ajax({
      url : "<?php echo site_url('h1/po_dealer/cari_jenis')?>",
      type:"POST",
      data:"bulan="+bulan+"&tahun="+tahun,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#jenis_po").val(data[0]);
        cek_jenis();                        
      }        
  })
}
function auto(){
  var po_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('h1/po_dealer/cari_id')?>",
      type:"POST",
      data:"po="+po_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        if(data[1] != 'nihil'){
          $("#id_po").val(data[1]);                                            
        }else{
          $("#id_po").val(data[0]);                            
        }        
        cek_jenis();                                     
      }        
  })
}
function cancel_tr(){
  var id_po_js=document.getElementById("id_po").value; 
  if (confirm("Are you sure to cancel this transaction...?") == true) {
      $.ajax({
        url : "<?php echo site_url('h1/po_dealer/cancel_po')?>",
        type:"POST",
        data:"id_po="+id_po_js,   
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
  var id_item_js  = $("#id_item").val();                       
  var bulan       = $("#bulan").val();                       
  var tahun       = $("#tahun").val();                       
  var id_dealer   = $("#id_dealer").val();
  if(id_dealer == ''){
    alert("Isikan data dengan lengkap");
    return false;
  }                       
  $.ajax({
      url: "<?php echo site_url('h1/po_dealer/cek_item')?>",
      type:"POST",
      data:"id_item="+id_item_js+"&bulan="+bulan+"&tahun="+tahun+"&id_dealer="+id_dealer,
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#id_item").val(data[1]);                
            $("#tipe").val(data[2]);                
            $("#warna").val(data[3]);
            $("#on_hand").val(data[4]);                        
            $("#qty_niguri_fix").val(data[5]);                        
            $("#qty_po_fix").val(data[6]);
            $("#qty_po_t1").val(data[7]);
            $("#qty_po_t2").val(data[8]);
          }else{
            alert(data[0]);
          }
      } 
  })
}
function hide_po(){
    $("#tampil_po").hide();
}
function kirim_data_po_reg(){    
  $("#tampil_po").show();
  var id_po = document.getElementById("id_po").value;   
  var mode = document.getElementById("mode").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_po="+id_po+"&mode="+mode;                           
     xhr.open("POST", "h1/po_dealer/t_po_reg", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_po").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_po_add(){    
  $("#tampil_po").show();
  var id_po = document.getElementById("id_po").value;   
  var mode = document.getElementById("mode").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_po="+id_po+"&mode="+mode;                           
     xhr.open("POST", "h1/po_dealer/t_po_add", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_po").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function tes(){
  alert("hello");
}
function simpan_po(){
  var jenis_po = document.getElementById("jenis_po").value;
  if(jenis_po == 'PO Reguler'){
    var id_po               = document.getElementById("id_po").value;   
    var id_item             = document.getElementById("id_item").value;   
    var qty_po_fix          = document.getElementById("qty_po_fix").value;   
    var qty_po_t1           = document.getElementById("qty_po_t1").value;   
    var qty_po_t2           = document.getElementById("qty_po_t2").value;
    var bulan               = $("#bulan").val();                       
    var tahun               = $("#tahun").val();                                 
    //alert(id_po);
    if (id_po == "" || id_item == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('h1/po_dealer/save_po_reg')?>",
            type:"POST",
            data:"id_po="+id_po+"&id_item="+id_item+"&qty_po_fix="+qty_po_fix+"&qty_po_t1="+qty_po_t1+"&qty_po_t2="+qty_po_t2+"&bulan="+bulan+"&tahun="+tahun,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_po_reg();
                    kosong();                
                }else if(data[0]=="niguri"){
                    alert("Qty PO Fix item ini melebihi QTY Niguri Fix");
                    $("#qty_po_fix").val("");
                }else if(data[0]=="po_fix"){
                    alert("Qty PO Fix item ini melebihi batas maksimum yang telah ditentukan");
                    $("#qty_po_fix").val("");
                }else if(data[0]=="po_t1"){
                    alert("Qty PO T1 item ini melebihi batas maksimum yang telah ditentukan");
                    $("#qty_po_t1").val("");
                }else{
                    alert(data[0]);
                    kosong();                      
                }                
            }
        })    
    }
  }else if(jenis_po == 'PO Additional'){
    var id_po               = document.getElementById("id_po").value;   
    var id_item             = document.getElementById("id_item").value;   
    var qty_order           = document.getElementById("qty_order").value;       
    //alert(id_po);
    if (id_po == "" || id_item == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
      $.ajax({
          url : "<?php echo site_url('h1/po_dealer/save_po_add')?>",
          type:"POST",
          data:"id_po="+id_po+"&id_item="+id_item+"&qty_order="+qty_order,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  kirim_data_po_add();
                  kosong();                
              }else{
                  alert('Item ini sudah ditambahkan');
                  kosong();                      
              }                
          }
      })    
    }
  }
}

function kosong(args){
  $("#id_item").val("");
  $("#warna").val("");   
  $("#tipe").val("");   
  $("#qty_po_t1").val("");   
  $("#qty_po_t2").val("");   
  $("#qty_order").val("");   
  $("#qty_po_fix").val("");   
}
function hapus_po(a,b){ 
    var id_po_detail  = a;   
    var id_item   = b;       
    $.ajax({
        url : "<?php echo site_url('h1/po_dealer/delete_po')?>",
        type:"POST",
        data:"id_po_detail="+id_po_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              cek_jenis();
            }
        }
    })
}
function edit_po_add(id){    
  //Ajax Load data from ajax
  $.ajax({
      url : "<?php echo site_url('h1/po_dealer/cari_po_add')?>",
      type:"POST",
      data:"id="+id,      
      success: function(msg)
      { 
          data=msg.split("|");
          $('[name="id_item"]').val(data[0]);          
          $('[name="tipe_ahm"]').val(data[1]);                    
          $('[name="warna"]').val(data[2]);                    
          $('[name="qty_order"]').val(data[3]);                    
          $('[name="id_po_detail"]').val(data[4]);                                                
          $('[name="id_po"]').val(data[5]);                              
          $('[name="mode"]').val(data[6]);                              
          $('#modal_po_add').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Data PO'); // Set title to Bootstrap modal title

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error get data from ajax');
      }
  });
}
function edit_po_reg(id){    
  //Ajax Load data from ajax
  $.ajax({
      url : "<?php echo site_url('h1/po_dealer/cari_po_reg')?>",
      type:"POST",
      data:"id="+id,      
      success: function(msg)
      { 
          data=msg.split("|");
          $('[name="id_item"]').val(data[0]);          
          $('[name="tipe_ahm"]').val(data[1]);                    
          $('[name="warna"]').val(data[2]);                    
          $('[name="qty_po_fix"]').val(data[3]);                    
          $('[name="qty_po_t1"]').val(data[4]);                    
          $('[name="qty_po_t2"]').val(data[5]);                    
          $('[name="id_po_detail"]').val(data[6]);                                                
          $('[name="id_po"]').val(data[7]);                              
          $('[name="mode"]').val(data[8]);                              
          $('#modal_po_reg').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Data PO'); // Set title to Bootstrap modal title

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error get data from ajax');
      }
  });
}
</script>