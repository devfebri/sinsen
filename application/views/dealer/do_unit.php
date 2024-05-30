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
<body onload="refresh()">
<?php
}else{
?>
<body onload="sembunyi()">
<?php 
}
?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">DO Unit</li>
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
          <a href="h1/do_unit">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>          
          <button onclick="refresh()" class="btn btn-warning btn-flat margin"><i class="fa fa-refresh"></i> Refresh</button>          
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
            <form class="form-horizontal" action="h1/do_unit/save" method="post" enctype="multipart/form-data">
              <div class="box-body">    
                <div class="form-group">
                  <input id="mode" value="insert" type="hidden">
                   <?php /* <label for="inputEmail3" class="col-sm-2 control-label">Dealer/POS</label>
                <div class="col-sm-4">
                    <select class="form-control" name="sumber_do" id="sumber_do" onchange="cek_jenis()">
                      <option value='dealer'>Dealer</option>
                      <option value='pos'>POS Dealer</option>
                    </select>
                  </div> */?>
                  <input type="hidden" name="sumber_do" id="sumber_do" value="dealer">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="id_dealer" id="id_dealer_pilih" onchange="resetSource()">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->nama_dealer</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>                 
                </div>           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Source</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_do" onchange="cek_jenis()" id="jenis_do">
                      <option value="">- choose -</option>
                      <option value="po_indent">PO Indent</option>
                      <option value="po_reguler">PO Reguler</option>
                      <option value="po_additional">PO Additional</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" required name="id_gudang">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_gudang->result() as $val) {
                        echo "
                        <option value='$val->id_gudang'>$val->gudang</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div>                 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.DO (Suggest)</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control"  id="no_do" readonly placeholder="No.DO" name="no_do">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal" name="tanggal" value="<?php echo date("Y-m-d") ?>" class="form-control">
                  </div>
                </div>           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengambilan</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="pengambilan" name="pengambilan">
                      <option value="Diambil">Diambil</option>                                            
                      <option value="Tidak Diambil">Tidak Diambil</option>                                            
                    </select>
                  </div>                  
                  
                  <!-- No PO Indent -->
                  <div id="indent">                  
                    <label for="inputEmail3" id="label_do" class="col-sm-2 control-label">No Indent</label>
                    <div class="col-sm-3">
                      <select class="form-control select2" id="no_indent" name="no_indent">
                        <option value="">- choose -</option>                        
                      </select>
                    </div>  
                    <button type="button" onclick="cari_dealer()" class="btn btn-flat btn-primary btn-sm">Generate</button>
                  </div>                                

                  <!-- No PO -->
                  <div id="po">                  
                    <label for="inputEmail3" id="label_do" class="col-sm-2 control-label">No.PO Dealer</label>
                    <div class="col-sm-3">
                      <select class="form-control select2" id="no_po" name="no_po">
                        <option value="">- choose -</option>                        
                      </select>
                    </div>  
                    <button type="button" onclick="cari_dealer2()" class="btn btn-flat btn-primary btn-sm">Generate</button>
                  </div>                                


                </div>      
    
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="id_dealer_md" readonly id="id_dealer_md" class="form-control">
                    <input type="hidden" name="id_dealer" id="id_dealer" class="form-control">
                    <!--select class="form-control" name="id_dealer" onchange="cari_dealer()" id="id_dealer">
                      <option value="">- choose -</option>
                      <?php 
                      foreach($dt_dealer->result() as $val) {
                        echo "
                        <option value='$val->id_dealer'>$val->kode_dealer_md</option>;
                        ";
                      }
                      ?>
                    </select-->
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="nama_dealer" readonly id="nama_dealer" class="form-control">
                  </div>
                </div>                                                                                                               
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Keterangan" id="ket" name="ket">
                  </div>
                </div>    
                
                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_do"></span>                                                                                  
                  
                  
                </div>                
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
    }elseif($set=="edit"){
      $row = $dt_do->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/do_unit">
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
        



        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/do_unit/update" method="post" enctype="multipart/form-data">
              <div class="box-body">         
                <div class="form-group">
                  <input id="mode" value="edit" type="hidden">
                  <label for="inputEmail3" class="col-sm-2 control-label">Source</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_do" onselect="cek_jenis()" id="jenis_do" readonly>
                      <?php 
                      if($row->source == 'po_reguler'){
                        echo "
                        <option value='po_reguler' selected>PO Reguler</option>
                        ";
                      }elseif($row->source == 'po_additional'){
                        echo "
                        <option value='po_additional' selected>PO Additional</option>                        
                        ";
                      }elseif($row->source == 'po_indent'){
                        echo "
                        <option value='po_indent' selected>PO Indent</option>                                                
                        ";
                      }
                      ?>                      
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_gudang">
                      <option value="<?php echo $row->id_gudang ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_gudang","id_gudang",$row->id_gudang)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->gudang;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_gudang = $this->m_admin->kondisi("ms_gudang","id_gudang != '$row->id_gudang'");                                                
                      foreach($dt_gudang->result() as $val) {
                        echo "
                        <option value='$val->id_gudang'>$val->gudang</option>;
                        ";
                      }
                      ?>
                    </select>
                  </div>
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.DO</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" value="<?php echo $row->no_do ?>" id="no_do" readonly placeholder="No.DO" name="no_do">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal2" name="tanggal" value="<?php echo $row->tgl_do ?>" class="form-control">
                  </div>
                </div>           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengambilan</label>
                  <div class="col-sm-4">
                    <select class="form-control" id="pengambilan" name="pengambilan">
                      <?php 
                      if($row->pengambilan == 'Tidak Diambil'){
                        echo "
                        <option value='Tidak Diambil' selected>Tidak Diambil</option>                        
                        <option value='Diambil'>Diambil</option>                        
                        ";
                      }elseif($row->pengambilan == 'Diambil'){
                        echo "
                        <option value='Diambil' selected>Diambil</option>                        
                        <option value='Tidak Diambil'>Tidak Diambil</option>                        
                        ";
                      }
                      ?>
                    </select>
                  </div>                  
                  
                  <!-- No PO Indent -->
                  <div id="indent">                  
                    <label for="inputEmail3" id="label_do" class="col-sm-2 control-label">No Indent</label>
                    <div class="col-sm-4">
                      <select class="form-control" id="no_indent" disabled name="no_po_indent">
                        <option value="">- choose -</option>
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("tr_po_dealer_indent","id_indent",$row->no_do)->row();                                 
                        $t = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$dt_cust->id_dealer'")->row();
                        if(isset($dt_cust)){
                          echo $dt_cust->id_spk." | ".$dt_cust->tgl." | ".$t->nama_dealer;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </select>
                    </div>  
                  </div>                                

                  <!-- No PO -->
                  <div id="po">                  
                    <label for="inputEmail3" id="label_do" class="col-sm-2 control-label">No.PO Dealer</label>
                    <div class="col-sm-4">
                      <select class="form-control" id="no_po_edit" name="no_po">
                        <option value="<?php echo $row->no_po ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("tr_po_dealer","id_po",$row->no_po)->row();                                 
                        $t = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$dt_cust->id_dealer'")->row();
                        if(isset($dt_cust)){
                          echo $dt_cust->id_po." | ".$dt_cust->tgl." | ".$t->nama_dealer;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <!--?php 
                      $rt = $this->db->query("SELECT * FROM tr_po_dealer WHERE id_po != '$row->no_po' AND status = 'input'");                                                
                        foreach($rt->result() as $val) {
                          $t = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$val->id_dealer'")->row();
                          echo "
                          <option value='$val->id_po'>$val->id_po | $val->tgl | $t->nama_dealer</option>;
                          ";
                        }
                      ?-->
                      </select>
                    </div>  
                    <!--button type="button" class="btn btn-flat btn-primary btn-sm">Generate</button-->
                  </div>                                


                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer/POS</label>
                  <div class="col-sm-4">
                    <?php 
                    $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                    if(isset($dt_cust)){
                      $dealer = $dt_cust->nama_dealer;
                      $id_dealer_md = $dt_cust->kode_dealer_md;
                      $id_dealer = $dt_cust->id_dealer;
                    }else{
                      $dealer = "";
                      $id_dealer_md = "";
                      $id_dealer = "";
                    }
                    ?>
                    <input type="text" name="id_dealer_md" value="<?php echo $id_dealer_md ?>" id="id_dealer_md" class="form-control" readonly>
                    <input type="hidden" name="id_dealer" value="<?php echo $id_dealer ?>" id="id_dealer">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer/POS</label>
                  <div class="col-sm-4">
                    
                    <input type="text" name="nama_dealer" value="<?php echo $dealer ?>" id="nama_dealer" class="form-control" readonly>
                  </div>
                </div>  
                                                                                                                                                         
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" value="<?php echo $row->ket ?>" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                
                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_do_edit"></span>                                                                                  
                  
                  
                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="save" value="update" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Update All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_do->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/do_unit">
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
              <div class="box-body">                          
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Source</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_do" onchange="cek_jenis()" id="jenis_do" readonly>
                      <?php 
                      if($row->source == 'po_reguler'){
                        echo "
                        <option value='po_reguler' selected>PO Reguler</option>
                        ";
                      }elseif($row->source == 'po_additional'){
                        echo "
                        <option value='po_additional' selected>PO Additional</option>                        
                        ";
                      }elseif($row->source == 'po_indent'){
                        echo "
                        <option value='po_indent' selected>PO Indent</option>                                                
                        ";
                      }
                      ?>                      
                    </select>                                  
                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">                  
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_gudang","id_gudang",$row->id_gudang)->row();                                 
                        if(isset($dt_cust)){
                          $gudang = $dt_cust->gudang;
                        }else{
                          $gudang = "";
                        }
                        ?>                      
                      <input type="text" class="form-control" readonly name="source" value="<?php echo $gudang ?>">                                                                
                  </div>
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.DO</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $row->no_do ?>" id="no_do" readonly placeholder="No.DO" name="no_do">                    
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" id="tanggal" readonly name="tanggal" value="<?php echo $row->tgl_do ?>" class="form-control">
                  </div>
                </div>           
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Pengambilan</label>
                  <div class="col-sm-4">
                    <input type="text" readonly name="tanggal" value="<?php echo $row->pengambilan ?>" class="form-control">                    
                  </div>                  
                  <?php 
                  if($row->source == 'po_reguler' or $row->source == 'po_additional'){
                  ?>
                  <!-- No PO -->
                  <div id="po">                  
                    <label for="inputEmail3" id="label_do" class="col-sm-2 control-label">No.PO Dealer</label>
                    <div class="col-sm-4">                      
                      <?php 
                      $dt_cust    = $this->m_admin->getByID("tr_po_dealer","id_po",$row->no_po)->row();                                 
                      if(isset($dt_cust)){
                        $po = $dt_cust->id_po;
                      }else{
                        $po = "";
                      }
                      ?>    
                      <input type="text" readonly name="tanggal" value="<?php echo $po ?>" class="form-control">                                                              
                    </div>  
                  </div> 
                  <?php } ?>                  
                                                 


                </div>                
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer/POS</label>
                  <div class="col-sm-4">                    
                    <?php 
                    $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                    if(isset($dt_cust)){
                      $id_dealer = $dt_cust->kode_dealer_md;
                    }else{
                      $id_dealer = "";
                    }
                    ?>
                    <input type="text" readonly name="tanggal" value="<?php echo $id_dealer ?>" class="form-control">                                                              
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer/POS</label>
                  <div class="col-sm-4">
                    <?php 
                    $dt_cust    = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();                                 
                    if(isset($dt_cust)){
                      $dealer = $dt_cust->nama_dealer;
                    }else{
                      $dealer = "";
                    }
                    ?>
                    <input type="text" name="nama_dealer" value="<?php echo $dealer ?>" id="nama_dealer" class="form-control" readonly>
                  </div>
                </div>  
                                                                                                                                                         
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control" value="<?php echo $row->ket ?>" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                
                <hr>                
                                                    
                <table id="myTable" class="table myTable1 order-list" border="0">
                  <thead>
                    <tr>
                      <th width="7%">ID Item</th>
                      <th width="15%">Tipe</th>
                      <th width="10%">Warna</th>
                      <th width="10%">Qty On Hand</th>      
                      <th width="5%">Qty RFS</th>        
                      <th width="5%">Qty Order</th>        
                      <th width="5%">Qty DO</th>  
                      <th width="10%">Harga</th>  
                      <th width="10%">Total</th>  
                      <th width="10%">No Mesin</th>  
                    </tr>
                  </thead> 
                </table>

                <table id="example2" class="table myTable1 table-bordered table-hover">
                  <?php   
                  $isi_nosin = "";                  
                  $q=0;$t=0;$mode=0;$jum = 0;
                  $dt_do_reg = $this->db->query("SELECT tr_do_po.*,tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail 
                    INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do INNER JOIN ms_item 
                    ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$row->no_do'");
                  foreach($dt_do_reg->result() as $row) {           
                    $total_harga = $row->harga * $row->qty_do;

                    $cek_po = $this->db->query("SELECT * FROM tr_po_dealer_detail WHERE id_po = '$row->no_po' AND id_item = '$row->id_item'")->row();
                    $cek_indent = $this->db->query("SELECT * FROM tr_po_dealer_indent INNER JOIN tr_do_indent_detail ON tr_po_dealer_indent.id_indent=tr_do_indent_detail.id_indent 
                          WHERE tr_do_indent_detail.no_do = '$row->no_do'")->row();    
                    $jenis_do = $row->source;
                    if($jenis_do == 'po_reguler'){
                      $qty = $cek_po->qty_po_fix;
                    }elseif($jenis_do == 'po_additional') {
                      if($cek_po->qty_order != ""){
                        $qty = $cek_po->qty_order;
                      }else{
                        $qty = 0;
                      }
                    }elseif($jenis_do == 'po_indent') {
                      $qty = $cek_indent->qty;
                    }
                    echo "
                    <tr>
                      <td width='7%'>$row->id_item</td>
                      <td width='15%'>$row->tipe_ahm</td>
                      <td width='10%'>$row->warna</td>
                      <td width='10%'>$row->qty_on_hand</td>
                      <td width='5%'>$row->qty_rfs</td>
                      <td width='5%'>$qty</td>
                      <td width='5%'>$row->qty_do</td>
                      <td width='10%'>".mata_uang2($row->harga)."</td>
                      <td width='10%'>".mata_uang2($total_harga)."</td>                      
                      <td width='10%'>";
                      

                      if($row->qty_do > 0){
                        for ($i=0;$i <= $row->qty_do-1;$i++) {                       
                          $th = date("Y");
                          $array_isi = explode("|", $isi_nosin);
                          foreach ($array_isi as $key){
                            $cek = $this->db->query("SELECT no_mesin,left(fifo,4) as th FROM tr_scan_barcode WHERE id_item ='$row->id_item' AND status = 1 AND tipe = 'RFS' AND LEFT(fifo,4) <= '$th' AND no_mesin <> '$key' ORDER BY fifo ASC LIMIT $i,1");                          
                          }
                          if($cek->num_rows() > 0){
                            foreach ($cek->result() as $isi) {                                                            
                              echo $isi->no_mesin."-".$isi->th."<br>";
                              $jum = $jum+$cek->num_rows();
                              $isi_nosin = $isi->no_mesin;                                                                                                                                              
                            }
                          }else{
                            $mode = 1;
                          }
                          
                        }
                      }


                      echo "
                      </td>                      
                    </tr>";    

                    $q = $q + $row->qty_do;
                    $t = $t + $total_harga;
                    }
                  ?>  
                  <?php 
                  if ($jum != $q or $jum > $q) {
                          	$mode=1;
                          }
                    ?>
                  <tfoot>
                    <td colspan="6" align="right"><b>Total</b></td>
                    <td><b><?php echo $q ?></b></td>
                    <td></td>
                    <td><b><?php echo mata_uang2($t) ?></b></td>
                    <td></td>
                  <tfoot>
                </table>
                  
                  
              </div><!-- /.box-body -->          
              <?php //echo $row->qty_do ?>

              
                  <?php if (!!$st): ?>
                  	<div class="box-footer">
                  		<?php 
		                  if($st == 'approve' and $mode==0){
		                  ?>          
		                  <div class="col-sm-2">
		                </div>
		                <div class="col-sm-10">        
		                  <a href='h1/do_unit/approve?no_do=<?php echo $row->no_do ?>' onclick="return confirm('Are you sure to approve all data?')" name="save" value="approve" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Approve All</a>
		                  
		                  <a href='h1/do_unit/reject?no_do=<?php echo $row->no_do ?>' onclick="return confirm('Are you sure to reject all data?')" name="save" value="reject" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject All</a>        </div>          
		                  <?php }else{ ?>
		                  		<div class="callout callout-danger">
					                Maaf Approval Tidak Bisa Dilakukan, Karena Unit Yang Tersedia Hanya <?php echo "$jum"; ?> Unit. Silahkan Lakukan Update Data.
					            </div>
		                 <?php } ?>              
                  <?php endif ?>
                
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
          <a href="h1/do_unit/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No.DO</th> 
              <th>Jenis DO</th>             
              <th>Kode Dealer</th>
              <th>Nama Dealer</th>
              <th>Tanggal</th>
              <th>Jumlah Harga</th>              
              <th>Jumlah Unit</th>
              <th>Status DO</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_do->result() as $row) {       
            if($row->status=='input'){
              $status = "<span class='label label-warning'>waiting approval</span>";
            }elseif($row->status=='approved'){
              $status = "<span class='label label-success'>$row->status</span>";
            }elseif($row->status=='rejected'){
              $status = "<span class='label label-danger'>$row->status</span>";
            }
            $c_unit = $this->db->query("SELECT SUM(qty_do) AS jum, SUM(qty_do * harga) AS tot FROM tr_do_po_detail WHERE no_do = '$row->no_do'");
            if($c_unit->num_rows() > 0){
              $i_unit = $c_unit->row();
              $unit = $i_unit->jum;
              $harga = $i_unit->tot;
            }else{
              $unit = 0;
              $harga = 0;
            }

            $dealer = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer = '$row->id_dealer'")->row();
            $id_user = $this->session->userdata("id_user");
            $cek = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_user_group ON ms_user_group.id_user_group=ms_user.id_user_group
                WHERE ms_user.id_user = '$id_user'");
            if($cek->num_rows() > 0){
              $g = $cek->row();
              $level = $g->user_group;
            }else{
              $level = "";
            }
            $hasil = "";
            $tom = $this->m_admin->set_tombol($id_menu,$group,"approval");
            $tampil = "<a data-toggle=\"tooltip\" title=\"Approval Data\" $tom class=\"btn btn-success btn-sm btn-flat\" href=\"h1/do_unit/detail?s=approve&id=$row->no_do\"><i class=\"fa fa-check\"></i></a>";
            //tampil2 = "<a data-toggle=\"tooltip\" title=\"Reject Data\"  class=\"btn btn-danger btn-sm btn-flat\" href=\"h1/do_unit/detail?s=reject&id=$row->no_do\"><i class=\"fa fa-close\"></i></a>";
            


          echo "          
            <tr>
              <td>$no</td>
              <td>
                <a href='h1/do_unit/detail?id=$row->no_do'>
                  $row->no_do
                </a>
              </td>
              <td>".ucwords(str_replace("_"," ",$row->source))."</td>
              <td>$dealer->kode_dealer_md</td>
              <td>$row->nama_dealer</td>
              <td>$row->tgl_do</td>
              <td>".mata_uang($harga)."</td>              
              <td>$unit</td>              
              <td>$status</td>              
              <td>";
              if($row->status=='input'){
              ?>
                <!--a data-toggle='tooltip' title="Verify" onclick="return confirm('Are you sure to approve this transaction?')" class="btn btn-warning btn-sm btn-flat" href="h1/do_unit/verify?id=<?php echo $row->no_do ?>"><i class="fa fa-check"></i></a-->
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="h1/do_unit/delete?id=<?php echo $row->no_do ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"update"); ?> title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='h1/do_unit/edit?id=<?php echo $row->no_do ?>'><i class='fa fa-edit'></i></a>
                <?php echo $tampil ?>                
              <?php }elseif($row->status=='rejected' AND ($level == 'admin' OR $level == 'Super Admin')){ ?>
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"delete"); ?> title="Delete Data" onclick="return confirm('Are you sure to delete this data?')" class="btn btn-danger btn-sm btn-flat" href="h1/do_unit/delete?id=<?php echo $row->no_do ?>"><i class="fa fa-trash-o"></i></a>
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"update"); ?> title="Edit Data" class='btn btn-primary btn-sm btn-flat' href='h1/do_unit/edit?id=<?php echo $row->no_do ?>'><i class='fa fa-edit'></i></a>                
              <?php } ?>
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


<script type="text/javascript">
function sembunyi(){
  $("#indent").hide();    
  $("#po").hide();        
}
function cek_jenis(){
  auto();  
  var jenis_do = document.getElementById("jenis_do").value;
  if(jenis_do == 'po_indent'){
    $("#indent").show();    
    $("#po").hide();        
    kirim_data_do_ind();    
  }else if(jenis_do == 'po_reguler'){
    $("#indent").hide();    
    $("#po").show();        
    kirim_data_do_reg();
  }if(jenis_do == 'po_additional'){
    $("#indent").hide();    
    $("#po").show();        
    kirim_data_do_add();  
  }
  kosong();
  ambil_slot();
}

function refresh(){
  var jenis_do = document.getElementById("jenis_do").value;
  if(jenis_do == 'po_indent'){
    $("#indent").show();    
    $("#po").hide();        
    kirim_data_do_ind_edit();    
  }else if(jenis_do == 'po_reguler'){
    $("#indent").hide();    
    $("#po").show();        
    //kirim_data_do_reg();    
    kirim_data_do_reg_edit();    
  }if(jenis_do == 'po_additional'){
    $("#indent").hide();    
    $("#po").show();        
    kirim_data_do_add_edit();    
  }else{
    return false;
  } 
}
function ambil_slot(){
  var jenis_do  = $("#jenis_do").val(); 
  var sumber_do = $("#sumber_do").val(); 
  var sumber_do = $("#sumber_do").val();
  var id_dealer = $("#id_dealer_pilih").val(); 
  $.ajax({
    url : "<?php echo site_url('h1/do_unit/get_slot')?>",
    type:"POST",
    data:"jenis_do="+jenis_do+"&sumber_do="+sumber_do+"&id_dealer="+id_dealer,
    cache:false,   
    success:function(msg){            
      $("#no_po").html(msg);      
      $("#no_indent").html(msg);      
    }
  })  
}

function cek_bulan(){
  var bulan = document.getElementById("bulan").value;
  var tahun = document.getElementById("tahun").value;
  //$("#jenis_po").val(bulan);
  $.ajax({
      url : "<?php echo site_url('h1/do_unit/cari_jenis')?>",
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
  var jenis_do = document.getElementById("jenis_do").value; 
  $.ajax({
      url : "<?php echo site_url('h1/do_unit/cari_id')?>",
      type:"POST",
      data:"jenis_do="+jenis_do,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_do").val(data[0]);
        //kirim_data_po();     
        //cek_jenis();                   
      }        
  })
}
function cari_dealer(){
  var id_indent = document.getElementById("no_indent").value; 
  var sumber_do = document.getElementById("sumber_do").value;     
  if(id_indent == ''){
    alert("Pilih No Indent terlebih dulu");
    return false;
  }
  $.ajax({
      url : "<?php echo site_url('h1/do_unit/cari_dealer')?>",
      type:"POST",
      data:"id_indent="+id_indent+"&sumber_do="+sumber_do,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_dealer_md").val(data[0]);
        $("#nama_dealer").val(data[1]);
        $("#ket").val(data[2]);
        $("#id_dealer").val(data[3]);
        kirim_data_do_ind();     
        //cek_jenis();                   
      }        
  })
}
function cari_dealer2(){  
  var no_po     = document.getElementById("no_po").value;   
  var sumber_do = document.getElementById("sumber_do").value;     
  if(no_po == ''){
    alert("Pilih No PO terlebih dulu");
    return false;
  }
  $.ajax({
      url : "<?php echo site_url('h1/do_unit/cari_dealer2')?>",
      type:"POST",
      data:"no_po="+no_po+"&sumber_do="+sumber_do,
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_dealer_md").val(data[0]);
        $("#nama_dealer").val(data[1]);
        $("#ket").val(data[2]);
        $("#id_dealer").val(data[3]);
        kirim_data_do_reg();     
        //cek_jenis();                   
      }        
  })
}
function cancel_tr(){
  var id_po_js=document.getElementById("id_po").value; 
  if (confirm("Are you sure to cancel this transaction...?") == true) {
      $.ajax({
        url : "<?php echo site_url('h1/do_unit/cancel_po')?>",
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

function kalian(){
  var jumlah  = $("#jumlah").val();
  var hal     = $("#hal").val(); 
  for(i=1;i<=hal;i++){
    var qty_do  = $("#qty_do_"+i+"").val();            
    var harga  = $("#harga_"+i+"").val();                    
    total = Number(qty_do) * Number(harga);        
    $("#total_harga_"+i+"").val(total);           
    $("#total_harga_f_"+i+"").val(convertNoRupiah(total));               
  }
}
function kali_po(){  
  var isi_po  = $("#isi_po").val();
  var hal     = $("#hal").val(); 
  for(i=1;i<=hal;i++){
  //var i = 1;
    //if(isi_po == i){
      var qty_po  = $("#qty_po_"+i+"").val();        
      var qty_on  = $("#qty_on_"+i+"").val();        
      var qty_or  = $("#qty_or_"+i+"").val();        
      var qty_rfs = $("#qty_rfs_"+i+"").val();        
      var harga   = $("#harga_"+i+"").val();        
      if(parseInt(qty_po) > parseInt(qty_on)){      
        //alert(qty_po);  
        //alert(qty_on);  
        alert("Qty DO tidak boleh melebihi Qty On Hand");  
        $("#qty_po_"+i+"").val("");        
        $("#qty_po_"+i+"").focus();        
      //   // $("#total_po_"+i+"").val("");                
      //   break;
      }else if(parseInt(qty_po) > parseInt(qty_rfs)){      
        //alert(qty_po);  
        //alert(qty_on);  
        alert("Qty DO tidak boleh melebihi Qty RFS");  
        $("#qty_po_"+i+"").val("");        
        $("#qty_po_"+i+"").focus();        
      //   // $("#total_po_"+i+"").val("");                
      //   break;
      }else if(parseInt(qty_po) > parseInt(qty_or)){      
        //alert(qty_po);  
        //alert(qty_on);  
        alert("Qty DO tidak boleh melebihi Qty Order");  
        $("#qty_po_"+i+"").val("");        
        $("#qty_po_"+i+"").focus();        
      //   // $("#total_po_"+i+"").val("");                
      //   break;      
      }else if(harga == 0){
        alert("Harga tidak boleh 0");  
        $("#qty_po_"+i+"").val("");        
        $("#qty_po_"+i+"").focus();        
      //   // $("#total_po_"+i+"").val("");        
      //   break;
      }else{        
        total = qty_po * harga;
        //total_v = formatRupiah(total);
        $("#total_po_"+i+"").val(total);        
        //break;        
      } 
    //}     
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
  $.ajax({
      url: "<?php echo site_url('h1/do_unit/cek_item')?>",
      type:"POST",
      data:"id_item="+id_item_js+"&bulan="+bulan+"&tahun="+tahun,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#id_item").val(data[1]);                
            $("#tipe").val(data[2]);                
            $("#warna").val(data[3]);
            $("#qty_on_hand").val(data[4]);
            $("#qty_rfs").val(data[5]);
            $("#harga").val(data[6]);            
          }else{
            alert(data[0]);
          }
      } 
  })
}
function hide_po(){
    $("#tampil_po").hide();
}
function kirim_data_do_ind(){    
  $("#tampil_do").show();
  var id_indent = document.getElementById("no_indent").value;   
  var no_do     = document.getElementById("no_do").value; 
  var mode      = document.getElementById("mode").value; 
  var id_dealer = document.getElementById("id_dealer").value;         
  if(mode == 'insert'){
    var tanggal   = document.getElementById("tanggal").value;   
  }else{
    var tanggal   = document.getElementById("tanggal2").value;   
  }
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_indent="+id_indent+"&no_do="+no_do+"&mode="+mode+"&tanggal="+tanggal+"&id_dealer="+id_dealer;                           
     xhr.open("POST", "h1/do_unit/t_do_ind", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_do").innerHTML = xhr.responseText;                
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_do_ind_edit(){    
  $("#tampil_do_edit").show();
  var id_indent = document.getElementById("no_indent").value;   
  var no_do     = document.getElementById("no_do").value; 
  var mode      = document.getElementById("mode").value; 
  var id_dealer = document.getElementById("id_dealer").value;         
  if(mode == 'insert'){
    var tanggal   = document.getElementById("tanggal").value;   
  }else{
    var tanggal   = document.getElementById("tanggal2").value;   
  }
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_indent="+id_indent+"&no_do="+no_do+"&mode="+mode+"&tanggal="+tanggal+"&id_dealer="+id_dealer;                           
     xhr.open("POST", "h1/do_unit/t_do_ind_edit", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_do_edit").innerHTML = xhr.responseText;                
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_do_reg(){    
  $("#tampil_do").show();
  var no_po     = document.getElementById("no_po").value;   
  var jenis_do  = document.getElementById("jenis_do").value;
  var mode      = document.getElementById("mode").value;   
  if(mode == 'insert'){
    var tanggal   = document.getElementById("tanggal").value;   
  }else{
    var tanggal   = document.getElementById("tanggal2").value;   
  }  
  var id_dealer = document.getElementById("id_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_po="+no_po+"&id_dealer="+id_dealer+"&jenis_do="+jenis_do+"&tanggal="+tanggal;                           
     xhr.open("POST", "h1/do_unit/t_do_reg", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_do").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_do_reg_edit(){    
  $("#tampil_do_edit").show();
  //var no_po = "20171100001";
  var no_do = document.getElementById("no_do").value;  
  var mode      = document.getElementById("mode").value;     
  var tanggal   = document.getElementById("tanggal2").value; 
  var id_dealer = document.getElementById("id_dealer").value;     
  
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_do="+no_do+"&tanggal="+tanggal+"&id_dealer="+id_dealer;                           
     xhr.open("POST", "h1/do_unit/t_do_reg_edit", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_do_edit").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_do_add_edit(){    
  $("#tampil_do_edit").show();
  //var no_po = "20171100001";
  var no_do = document.getElementById("no_do").value;   
  var jenis_do  = document.getElementById("jenis_do").value;   
  var id_dealer = document.getElementById("id_dealer").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_do="+no_do+"&jenis_do="+jenis_do+"&id_dealer="+id_dealer;                           
     xhr.open("POST", "h1/do_unit/t_do_reg_edit", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_do_edit").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_do_add(){    
  $("#tampil_do").show();
  var no_do = document.getElementById("no_do").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_do="+no_do;                           
     xhr.open("POST", "h1/do_unit/t_do_add", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_do").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}

function kosong(args){
  $("#id_dealer_md").val("");
  $("#nama_dealer").val("");
  $("#ket").val("");
  $("#id_dealer").val("");
}
function hapus_do(a,b){ 
    var id_do_detail  = a;   
    var jenis         = 'do_reg';       
    $.ajax({
        url : "<?php echo site_url('h1/do_unit/delete_do')?>",
        type:"POST",
        data:"id_do_detail="+id_do_detail+"&jenis="+jenis,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              cek_jenis();
            }else{
              alert("Failed");
            }
        }
    })
}
function kosong_indent(args){
  $("#no_indent").val("");
  $("#id_dealer_md").val("");
  $("#nama_dealer").val("");
  $("#ket").val("");
  $("#id_dealer").val("");
  kirim_data_do_ind();
  ambil_slot();
}
function simpan_indent(i){    
  var qty_do        = $("#qty_do_"+i+"").val();            
  var no_do         = $("#no_do_"+i+"").val();            
  var id_item       = $("#id_item_"+i+"").val();            
  var id_indent     = $("#id_indent_"+i+"").val();            
  var qty_on_hand   = $("#qty_on_hand_"+i+"").val();            
  var qty_rfs       = $("#qty_rfs_"+i+"").val();            
  var harga         = $("#harga_"+i+"").val();              
  //alert(qty_do);
  //alert(jumlah);
  if (no_indent == "" || qty_do == "" || no_do == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/do_unit/save_indent')?>",
          type:"POST",
          data:"no_do="+no_do+"&id_item="+id_item+"&id_indent="+id_indent+"&qty_do="+qty_do+"&qty_on_hand="+qty_on_hand+"&qty_rfs="+qty_rfs+"&harga="+harga,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                kirim_data_do_ind();
                kosong_indent();                              
              }else{
                alert(data[0]);
                kosong_indent();
                kirim_data_do_ind();
              }                
          }
      })    
  }
}
function hapus_indent(a,b){ 
    var id_indent  = a;       
    var id_do      = b;           
    $.ajax({
        url : "<?php echo site_url('h1/do_unit/delete_indent')?>",
        type:"POST",
        data:"id_indent="+id_indent+"&id_do="+id_do,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){              
              kirim_data_do_ind();
              kosong_indent();                            
            }else{
              alert(data[0]);
              kosong_indent();
            }
        }
    })
}
function hapus_indent_edit(a,b){ 
    var id_indent  = a;       
    var id_do      = b;           
    $.ajax({
        url : "<?php echo site_url('h1/do_unit/delete_indent')?>",
        type:"POST",
        data:"id_indent="+id_indent+"&id_do="+id_do,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){              
              kirim_data_do_ind_edit();
              kosong_indent();                            
            }else{
              alert(data[0]);
              kosong_indent();
            }
        }
    })
}

function resetSource()
{
  $('#jenis_do').val('');
}
</script>